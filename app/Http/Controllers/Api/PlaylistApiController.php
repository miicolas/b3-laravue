<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlaylistResource;
use App\Models\Playlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaylistApiController extends Controller
{
    /**
     * Get all playlists for the authenticated user with their tracks.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        $playlists = Playlist::where('user_id', $user->id)
            ->with('tracks')
            ->get();

        return response()->json([
            'playlists' => PlaylistResource::collection($playlists),
        ]);
    }
}
