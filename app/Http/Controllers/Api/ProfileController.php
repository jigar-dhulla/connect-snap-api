<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UploadProfilePhotoRequest;
use App\Models\Event;
use App\Models\Profile;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        $profile = $this->getOrCreateProfile();

        return response()->json([
            'data' => $this->formatProfile($profile),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $profile = $this->getOrCreateProfile();

        $profile->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $this->formatProfile($profile->fresh()),
        ]);
    }

    public function uploadPhoto(UploadProfilePhotoRequest $request): JsonResponse
    {
        $profile = $this->getOrCreateProfile();

        if ($profile->profile_photo) {
            Storage::disk('public')->delete($profile->profile_photo);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');

        $profile->update(['profile_photo' => $path]);

        return response()->json([
            'message' => 'Photo uploaded successfully',
            'data' => [
                'profile_photo' => $profile->profile_photo,
                'profile_photo_url' => Storage::disk('public')->url($path),
            ],
        ]);
    }

    public function qrCode(): JsonResponse
    {
        $profile = $this->getOrCreateProfile();

        $qrCodeUrl = "connectsnap://u/{$profile->qr_code_hash}";

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrCodeUrl);

        return response()->json([
            'data' => [
                'qr_code_hash' => $profile->qr_code_hash,
                'qr_code_url' => $qrCodeUrl,
                'qr_code_svg' => base64_encode($qrCodeSvg),
            ],
        ]);
    }

    protected function getOrCreateProfile(): Profile
    {
        $user = auth()->user();
        $event = Event::active()->first();

        if (! $event) {
            abort(503, 'No active event available');
        }

        return Profile::firstOrCreate(
            ['user_id' => $user->id, 'event_id' => $event->id],
            ['qr_code_hash' => Str::random(32)]
        );
    }

    protected function formatProfile(Profile $profile): array
    {
        return [
            'id' => $profile->id,
            'event' => [
                'id' => $profile->event->id,
                'name' => $profile->event->name,
                'slug' => $profile->event->slug,
            ],
            'user' => [
                'id' => $profile->user->id,
                'name' => $profile->user->name,
                'email' => $profile->user->email,
            ],
            'phone' => $profile->phone,
            'company' => $profile->company,
            'job_title' => $profile->job_title,
            'bio' => $profile->bio,
            'profile_photo' => $profile->profile_photo,
            'profile_photo_url' => $profile->profile_photo
                ? Storage::disk('public')->url($profile->profile_photo)
                : null,
            'social_url' => $profile->social_url,
            'qr_code_hash' => $profile->qr_code_hash,
            'qr_code_url' => "connectsnap://u/{$profile->qr_code_hash}",
            'registered_at' => $profile->registered_at?->toISOString(),
        ];
    }
}
