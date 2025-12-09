<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TrackController extends Controller
{
    public function index(): Response
    {
        $tracks = Track::where('is_visible', true)->get();
        return Inertia::render('Tracks/Index', [
            'tracks' => $tracks,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Tracks/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'duration' => 'nullable|string|max:10',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        Track::create($validated);

        return redirect()->route('tracks')->with('success', 'Track créé avec succès!');
    }
}
