<?php

use App\Models\Event;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Event::factory()->create([
        'slug' => 'test-event',
        'name' => 'Test Event',
        'is_active' => true,
    ]);
});

describe('GET /api/profile', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->getJson('/api/profile')
            ->assertUnauthorized();
    });

    it('returns profile for authenticated user', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/profile');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'event' => ['id', 'name', 'slug'],
                    'user' => ['id', 'name', 'email'],
                    'phone',
                    'company',
                    'job_title',
                    'bio',
                    'profile_photo',
                    'profile_photo_url',
                    'social_url',
                    'qr_code_hash',
                    'qr_code_url',
                    'registered_at',
                ],
            ]);
    });

    it('auto-creates profile for active event if not exists', function () {
        $user = User::factory()->create();

        expect(Profile::where('user_id', $user->id)->exists())->toBeFalse();

        $this->actingAs($user)->getJson('/api/profile');

        expect(Profile::where('user_id', $user->id)->exists())->toBeTrue();
    });

    it('returns existing profile if already exists', function () {
        $user = User::factory()->create();
        $event = Event::active()->first();

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'company' => 'Test Company',
        ]);

        $response = $this->actingAs($user)->getJson('/api/profile');

        $response->assertSuccessful()
            ->assertJsonPath('data.company', 'Test Company')
            ->assertJsonPath('data.id', $profile->id);
    });
});

describe('PUT /api/profile', function () {
    it('updates profile successfully', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson('/api/profile', [
                'phone' => '+1234567890',
                'company' => 'Acme Inc',
                'job_title' => 'Developer',
                'bio' => 'Hello world',
                'social_url' => 'https://linkedin.com/in/test',
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('message', 'Profile updated successfully')
            ->assertJsonPath('data.phone', '+1234567890')
            ->assertJsonPath('data.company', 'Acme Inc')
            ->assertJsonPath('data.job_title', 'Developer')
            ->assertJsonPath('data.bio', 'Hello world')
            ->assertJsonPath('data.social_url', 'https://linkedin.com/in/test');
    });

    it('validates bio max length', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson('/api/profile', [
                'bio' => str_repeat('a', 251),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['bio']);
    });

    it('validates social_url is valid url', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson('/api/profile', [
                'social_url' => 'not-a-url',
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['social_url']);
    });

    it('allows partial updates', function () {
        $user = User::factory()->create();
        $event = Event::active()->first();

        Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'company' => 'Old Company',
            'job_title' => 'Old Title',
        ]);

        $response = $this->actingAs($user)
            ->putJson('/api/profile', [
                'company' => 'New Company',
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.company', 'New Company')
            ->assertJsonPath('data.job_title', 'Old Title');
    });
});

describe('POST /api/profile/photo', function () {
    it('uploads photo successfully', function () {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/profile/photo', [
                'photo' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('message', 'Photo uploaded successfully')
            ->assertJsonStructure([
                'data' => ['profile_photo', 'profile_photo_url'],
            ]);

        $profile = Profile::where('user_id', $user->id)->first();
        Storage::disk('public')->assertExists($profile->profile_photo);
    });

    it('requires photo field', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/profile/photo', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['photo']);
    });

    it('validates photo is an image', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/profile/photo', [
                'photo' => UploadedFile::fake()->create('document.pdf', 100),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['photo']);
    });

    it('validates photo max size', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/profile/photo', [
                'photo' => UploadedFile::fake()->image('large.jpg')->size(3000),
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['photo']);
    });

    it('deletes old photo when uploading new one', function () {
        Storage::fake('public');
        $user = User::factory()->create();
        $event = Event::active()->first();

        $oldPhoto = UploadedFile::fake()->image('old.jpg');
        $oldPath = $oldPhoto->store('profile-photos', 'public');

        Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'profile_photo' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($user)
            ->postJson('/api/profile/photo', [
                'photo' => UploadedFile::fake()->image('new.jpg'),
            ]);

        Storage::disk('public')->assertMissing($oldPath);
    });
});

describe('GET /api/profile/qr-code', function () {
    it('returns qr code data', function () {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/profile/qr-code');

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'qr_code_hash',
                    'qr_code_url',
                    'qr_code_svg',
                ],
            ]);

        $data = $response->json('data');
        expect($data['qr_code_url'])->toBe("connectsnap://u/{$data['qr_code_hash']}");
        expect(base64_decode($data['qr_code_svg']))->toContain('<svg');
    });

    it('returns consistent qr code for same profile', function () {
        $user = User::factory()->create();

        $response1 = $this->actingAs($user)->getJson('/api/profile/qr-code');
        $response2 = $this->actingAs($user)->getJson('/api/profile/qr-code');

        expect($response1->json('data.qr_code_hash'))
            ->toBe($response2->json('data.qr_code_hash'));
    });
});