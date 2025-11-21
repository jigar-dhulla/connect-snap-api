<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScanConnectionRequest;
use App\Http\Requests\UpdateConnectionNotesRequest;
use App\Models\Connection;
use App\Models\Event;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConnectionController extends Controller
{
    public function scan(ScanConnectionRequest $request): JsonResponse
    {
        $scannedProfile = Profile::where('qr_code_hash', $request->qr_code_hash)->first();

        if (! $scannedProfile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }

        $scannerProfile = $this->getOrCreateScannerProfile();

        if ($scannedProfile->user_id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot connect with yourself.',
            ], 422);
        }

        $existingConnection = Connection::where('scanner_profile_id', $scannerProfile->id)
            ->where('scanned_profile_id', $scannedProfile->id)
            ->first();

        if ($existingConnection) {
            return response()->json([
                'message' => 'You are already connected with this person.',
            ], 409);
        }

        $connection = Connection::create([
            'scanner_profile_id' => $scannerProfile->id,
            'scanned_profile_id' => $scannedProfile->id,
            'notes' => $request->notes,
            'met_at' => now(),
        ]);

        return response()->json([
            'message' => 'Connection created successfully',
            'data' => $this->formatConnection($connection),
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $event = Event::active()->first();

        if (! $event) {
            return response()->json(['data' => []]);
        }

        $userProfile = Profile::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if (! $userProfile) {
            return response()->json(['data' => []]);
        }

        $query = Connection::where('scanner_profile_id', $userProfile->id)
            ->with(['scannedProfile.user', 'scannedProfile.event']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('scannedProfile.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $connections = $query->latest('met_at')->get();

        return response()->json([
            'data' => $connections->map(fn ($connection) => $this->formatConnection($connection)),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $connection = Connection::with(['scannedProfile.user', 'scannedProfile.event'])->find($id);

        if (! $connection) {
            return response()->json([
                'message' => 'Connection not found.',
            ], 404);
        }

        if ($connection->scannerProfile->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to view this connection.',
            ], 403);
        }

        return response()->json([
            'data' => $this->formatConnection($connection),
        ]);
    }

    public function updateNotes(UpdateConnectionNotesRequest $request, int $id): JsonResponse
    {
        $connection = Connection::find($id);

        if (! $connection) {
            return response()->json([
                'message' => 'Connection not found.',
            ], 404);
        }

        if ($connection->scannerProfile->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this connection.',
            ], 403);
        }

        $connection->update(['notes' => $request->notes]);

        return response()->json([
            'message' => 'Notes updated successfully',
            'data' => $this->formatConnection($connection->fresh(['scannedProfile.user', 'scannedProfile.event'])),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $connection = Connection::find($id);

        if (! $connection) {
            return response()->json([
                'message' => 'Connection not found.',
            ], 404);
        }

        if ($connection->scannerProfile->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to delete this connection.',
            ], 403);
        }

        $connection->delete();

        return response()->json([
            'message' => 'Connection deleted successfully',
        ]);
    }

    protected function getOrCreateScannerProfile(): Profile
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

    protected function formatConnection(Connection $connection): array
    {
        $scannedProfile = $connection->scannedProfile;

        return [
            'id' => $connection->id,
            'scanned_profile' => [
                'id' => $scannedProfile->id,
                'name' => $scannedProfile->user->name,
                'company' => $scannedProfile->company,
                'job_title' => $scannedProfile->job_title,
                'bio' => $scannedProfile->bio,
                'profile_photo_url' => $scannedProfile->profile_photo
                    ? Storage::disk('public')->url($scannedProfile->profile_photo)
                    : null,
                'social_url' => $scannedProfile->social_url,
            ],
            'notes' => $connection->notes,
            'met_at' => $connection->met_at?->toISOString(),
        ];
    }
}
