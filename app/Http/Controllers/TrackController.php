<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTrackRequest;
use App\Http\Requests\UpdateTrackRequest;
use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TrackController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');

        $tracks = Track::query()
            ->where('is_visible', true)
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->get();

        return Inertia::render('Tracks/Index', [
            'tracks' => $tracks,
            'playlists' => \App\Models\Playlist::where('user_id', auth()->id())->get(['id', 'name', 'slug']),
            'filters' => $request->only(['search', 'genre']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tracks/Create');
    }

    public function store(StoreTrackRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('tracks/audio', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('tracks/thumbnails', 'public');
        }

        $created = Track::create($data);

        return redirect()->route('tracks.index')->with('success', 'Track créé avec succès!')->with('track', $created);
    }

    public function edit(Track $track): Response
    {
        return Inertia::render('Tracks/Edit', [
            'track' => $track,
        ]);
    }

    public function update(UpdateTrackRequest $request, Track $track)
    {
        $data = $request->validated();

        if ($request->hasFile('audio_file')) {
            // Delete old audio file
            if ($track->audio_file) {
                Storage::disk('public')->delete($track->audio_file);
            }
            $data['audio_file'] = $request->file('audio_file')->store('tracks/audio', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($track->thumbnail) {
                Storage::disk('public')->delete($track->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('tracks/thumbnails', 'public');
        }

        $track->update($data);

        return redirect()->route('tracks.index')->with('success', 'Track modifié avec succès!');
    }

    public function destroy(Track $track)
    {

        if ($track->audio_file) {
            Storage::disk('public')->delete($track->audio_file);
        }
        if ($track->thumbnail) {
            Storage::disk('public')->delete($track->thumbnail);
        }

        $deleted = $track->delete();

        return redirect()->route('tracks.index')->with('success', 'Track supprimé avec succès!')->with('deleted', $deleted);
    }
}
