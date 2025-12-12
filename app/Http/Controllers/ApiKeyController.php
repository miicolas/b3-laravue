<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiKey\DestroyApiKeyRequest;
use App\Http\Requests\ApiKey\StoreApiKeyRequest;
use App\Http\Requests\ApiKey\UpdateApiKeyRequest;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ApiKeyController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('ApiKeys/Index', [
            'apiKeys' => ApiKey::where('user_id', $request->user()->id)->get(),
        ]);
    }

    public function store(StoreApiKeyRequest $request)
    {
        $validated = $request->validated();

        $created = ApiKey::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(8),
            'key' => ApiKey::generateRandomKey(),
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('api-keys.index')->with('success', 'API key created successfully')->with('created', $created);
    }

    public function show(Request $request, ApiKey $apiKey)
    {
        if ($apiKey->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('ApiKeys/Show', [
            'apiKey' => $apiKey,
        ]);
    }

    public function update(UpdateApiKeyRequest $request, ApiKey $apiKey)
    {
        $validated = $request->validated();

        $apiKey->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(8),
        ]);

        return redirect()->route('api-keys.index')->with('success', 'API key updated successfully');
    }

    public function destroy(DestroyApiKeyRequest $request, ApiKey $apiKey)
    {
        $deleted = $apiKey->delete();

        return redirect()->route('api-keys.index')->with('success', 'API key deleted successfully')->with('deleted', $deleted);
    }
}
