<?php

use App\Models\Connection;
use App\Models\Event;
use App\Models\Profile;
use App\Models\User;

beforeEach(function () {
    $this->event = Event::factory()->create([
        'slug' => 'test-event',
        'name' => 'Test Event',
        'is_active' => true,
    ]);
});

describe('POST /api/connections/scan', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->postJson('/api/connections/scan')
            ->assertUnauthorized();
    });

    it('creates connection when scanning valid qr code', function () {
        $scanner = User::factory()->create();
        $scannedUser = User::factory()->create();

        $scannedProfile = Profile::factory()->create([
            'user_id' => $scannedUser->id,
            'event_id' => $this->event->id,
        ]);

        $response = $this->actingAs($scanner)
            ->postJson('/api/connections/scan', [
                'qr_code_hash' => $scannedProfile->qr_code_hash,
            ]);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'scanned_profile',
                    'notes',
                    'met_at',
                ],
            ]);

        $this->assertDatabaseHas('connections', [
            'scanned_profile_id' => $scannedProfile->id,
        ]);
    });

    it('requires qr_code_hash field', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/connections/scan', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['qr_code_hash']);
    });

    it('returns 404 for invalid qr code hash', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/connections/scan', [
                'qr_code_hash' => 'invalid-hash-that-does-not-exist',
            ])
            ->assertNotFound();
    });

    it('prevents scanning own qr code', function () {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $this->actingAs($user)
            ->postJson('/api/connections/scan', [
                'qr_code_hash' => $profile->qr_code_hash,
            ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'You cannot connect with yourself.');
    });

    it('prevents duplicate connections', function () {
        $scanner = User::factory()->create();
        $scannedUser = User::factory()->create();

        $scannerProfile = Profile::factory()->create([
            'user_id' => $scanner->id,
            'event_id' => $this->event->id,
        ]);

        $scannedProfile = Profile::factory()->create([
            'user_id' => $scannedUser->id,
            'event_id' => $this->event->id,
        ]);

        Connection::factory()->create([
            'scanner_profile_id' => $scannerProfile->id,
            'scanned_profile_id' => $scannedProfile->id,
        ]);

        $this->actingAs($scanner)
            ->postJson('/api/connections/scan', [
                'qr_code_hash' => $scannedProfile->qr_code_hash,
            ])
            ->assertConflict()
            ->assertJsonPath('message', 'You are already connected with this person.');
    });

    it('allows adding notes when scanning', function () {
        $scanner = User::factory()->create();
        $scannedUser = User::factory()->create();

        $scannedProfile = Profile::factory()->create([
            'user_id' => $scannedUser->id,
            'event_id' => $this->event->id,
        ]);

        $response = $this->actingAs($scanner)
            ->postJson('/api/connections/scan', [
                'qr_code_hash' => $scannedProfile->qr_code_hash,
                'notes' => 'Met at the Laravel talk',
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.notes', 'Met at the Laravel talk');
    });
});

describe('GET /api/connections', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->getJson('/api/connections')
            ->assertUnauthorized();
    });

    it('returns list of connections for authenticated user', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $otherProfiles = Profile::factory()->count(3)->create([
            'event_id' => $this->event->id,
        ]);

        foreach ($otherProfiles as $profile) {
            Connection::factory()->create([
                'scanner_profile_id' => $userProfile->id,
                'scanned_profile_id' => $profile->id,
            ]);
        }

        $response = $this->actingAs($user)
            ->getJson('/api/connections');

        $response->assertSuccessful()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'scanned_profile',
                        'notes',
                        'met_at',
                    ],
                ],
            ]);
    });

    it('returns empty array when user has no connections', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/connections')
            ->assertSuccessful()
            ->assertJsonCount(0, 'data');
    });

    it('supports search by name', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $john = User::factory()->create(['name' => 'John Doe']);
        $jane = User::factory()->create(['name' => 'Jane Smith']);

        $johnProfile = Profile::factory()->create([
            'user_id' => $john->id,
            'event_id' => $this->event->id,
        ]);
        $janeProfile = Profile::factory()->create([
            'user_id' => $jane->id,
            'event_id' => $this->event->id,
        ]);

        Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => $johnProfile->id,
        ]);
        Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => $janeProfile->id,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/connections?search=John');

        $response->assertSuccessful()
            ->assertJsonCount(1, 'data');
    });

    it('only returns connections for active event', function () {
        $user = User::factory()->create();

        $activeProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $inactiveEvent = Event::factory()->create(['is_active' => false]);
        $inactiveProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $inactiveEvent->id,
        ]);

        $connectionActive = Connection::factory()->create([
            'scanner_profile_id' => $activeProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $connectionInactive = Connection::factory()->create([
            'scanner_profile_id' => $inactiveProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $inactiveEvent->id])->id,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/connections');

        $response->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $connectionActive->id);
    });
});

describe('GET /api/connections/{id}', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->getJson('/api/connections/1')
            ->assertUnauthorized();
    });

    it('returns connection details', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $otherProfile = Profile::factory()->create([
            'event_id' => $this->event->id,
            'company' => 'Test Company',
            'job_title' => 'Developer',
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => $otherProfile->id,
            'notes' => 'Great conversation',
        ]);

        $response = $this->actingAs($user)
            ->getJson("/api/connections/{$connection->id}");

        $response->assertSuccessful()
            ->assertJsonPath('data.id', $connection->id)
            ->assertJsonPath('data.notes', 'Great conversation')
            ->assertJsonPath('data.scanned_profile.company', 'Test Company');
    });

    it('returns 404 for non-existent connection', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/connections/99999')
            ->assertNotFound();
    });

    it('returns 403 when accessing other users connection', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherUserProfile = Profile::factory()->create([
            'user_id' => $otherUser->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $otherUserProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $this->actingAs($user)
            ->getJson("/api/connections/{$connection->id}")
            ->assertForbidden();
    });
});

describe('PUT /api/connections/{id}/notes', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->putJson('/api/connections/1/notes')
            ->assertUnauthorized();
    });

    it('updates connection notes', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
            'notes' => 'Old notes',
        ]);

        $response = $this->actingAs($user)
            ->putJson("/api/connections/{$connection->id}/notes", [
                'notes' => 'Updated notes about this person',
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('message', 'Notes updated successfully')
            ->assertJsonPath('data.notes', 'Updated notes about this person');

        $this->assertDatabaseHas('connections', [
            'id' => $connection->id,
            'notes' => 'Updated notes about this person',
        ]);
    });

    it('validates notes max length', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $this->actingAs($user)
            ->putJson("/api/connections/{$connection->id}/notes", [
                'notes' => str_repeat('a', 501),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['notes']);
    });

    it('allows clearing notes', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
            'notes' => 'Some notes',
        ]);

        $response = $this->actingAs($user)
            ->putJson("/api/connections/{$connection->id}/notes", [
                'notes' => null,
            ]);

        $response->assertSuccessful()
            ->assertJsonPath('data.notes', null);
    });

    it('returns 403 when updating other users connection', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherUserProfile = Profile::factory()->create([
            'user_id' => $otherUser->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $otherUserProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $this->actingAs($user)
            ->putJson("/api/connections/{$connection->id}/notes", [
                'notes' => 'Trying to update',
            ])
            ->assertForbidden();
    });
});

describe('DELETE /api/connections/{id}', function () {
    it('returns 401 for unauthenticated user', function () {
        $this->deleteJson('/api/connections/1')
            ->assertUnauthorized();
    });

    it('deletes connection', function () {
        $user = User::factory()->create();
        $userProfile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $userProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/connections/{$connection->id}");

        $response->assertSuccessful()
            ->assertJsonPath('message', 'Connection deleted successfully');

        $this->assertDatabaseMissing('connections', [
            'id' => $connection->id,
        ]);
    });

    it('returns 403 when deleting other users connection', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $otherUserProfile = Profile::factory()->create([
            'user_id' => $otherUser->id,
            'event_id' => $this->event->id,
        ]);

        $connection = Connection::factory()->create([
            'scanner_profile_id' => $otherUserProfile->id,
            'scanned_profile_id' => Profile::factory()->create(['event_id' => $this->event->id])->id,
        ]);

        $this->actingAs($user)
            ->deleteJson("/api/connections/{$connection->id}")
            ->assertForbidden();
    });

    it('returns 404 for non-existent connection', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson('/api/connections/99999')
            ->assertNotFound();
    });
});

describe('GET /api/u/{qr_hash} (Public Profile)', function () {
    it('returns public profile without authentication', function () {
        $user = User::factory()->create(['name' => 'John Doe']);
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
            'company' => 'Acme Inc',
            'job_title' => 'Developer',
            'bio' => 'Hello world',
        ]);

        $response = $this->getJson("/api/u/{$profile->qr_code_hash}");

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'company',
                    'job_title',
                    'bio',
                    'profile_photo_url',
                    'social_url',
                ],
            ])
            ->assertJsonPath('data.name', 'John Doe')
            ->assertJsonPath('data.company', 'Acme Inc');
    });

    it('does not expose sensitive data in public profile', function () {
        $user = User::factory()->create();
        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'event_id' => $this->event->id,
            'phone' => '+1234567890',
        ]);

        $response = $this->getJson("/api/u/{$profile->qr_code_hash}");

        $response->assertSuccessful()
            ->assertJsonMissing(['email'])
            ->assertJsonMissing(['phone'])
            ->assertJsonMissing(['qr_code_hash']);
    });

    it('returns 404 for invalid qr hash', function () {
        $this->getJson('/api/u/invalid-hash-that-does-not-exist')
            ->assertNotFound();
    });
});
