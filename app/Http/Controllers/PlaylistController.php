<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\StorePlaylistRequest;
use App\Http\Requests\UpdatePlaylistRequest;
use App\Models\Playlist;
use Illuminate\Support\Facades\Storage;

class PlaylistController extends Controller
{
    public function index(Request $request): Response
    {

        $search = $request->input('search');
        $playlists = Playlist::query()
            ->where('user_id', auth()->user()->id)
            ->withCount('tracks')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->get();
        return Inertia::render('Playlists/Index', [
            'playlists' => $playlists,
        ]);
    }

    public function show(Playlist $playlist): Response
    {
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $playlist->load(['tracks' => function ($query) {
            $query->latest('playlist_track.created_at');
        }]);

        return Inertia::render('Playlists/Show', [
            'playlist' => $playlist,
            'tracks' => $playlist->tracks,
            'playlists' => Playlist::where('user_id', auth()->id())->get(['id', 'name', 'slug']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Playlists/Create');
    }

    public function store(StorePlaylistRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($data['image']) {
                Storage::delete($data['image']);
            }
            $data['image'] = $request->file('image')->store('playlists/images', 'public');
        }

        $data['user_id'] = auth()->user()->id;

        $created = Playlist::create($data);

        return redirect()->route('playlists.index')->with('success', 'Playlist créée avec succès!')->with('playlist', $created);
    }

    public function edit(Playlist $playlist): Response
    {
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Playlists/Edit', [
            'playlist' => $playlist,
        ]);
    }

    public function update(UpdatePlaylistRequest $request, Playlist $playlist)
    {
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($playlist->image) {
                Storage::delete($playlist->image);
            }
            $data['image'] = $request->file('image')->store('playlists/images', 'public');
        }

        $playlist->update($data);

        return redirect()->route('playlists.index')->with('success', 'Playlist modifiée avec succès!')->with('playlist', $playlist);
    }

    public function destroy(Playlist $playlist)
    {
        if ($playlist->user_id !== auth()->id()) {
            abort(403);
        }

        if ($playlist->image) {
            Storage::delete($playlist->image);
        }

        $deleted = $playlist->delete();

        return redirect()->route('playlists.index')->with('success', 'Playlist supprimée avec succès!')->with('deleted', $deleted);
    }
}
