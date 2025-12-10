<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Playlist;
use App\Models\Track;

class PlaylistTrackController extends Controller
{
    public function store(Request $request, Playlist $playlist)
    {
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);

        $playlist->tracks()->syncWithoutDetaching([$request->track_id]);

        return back()->with('success', 'Titre ajouté à la playlist.');
    }

    public function destroy(Playlist $playlist, Track $track)
    {
        $playlist->tracks()->detach($track->id);

        return back()->with('success', 'Titre retiré de la playlist.');
    }
}
