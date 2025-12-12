<?php

namespace App\Http\Requests\Track;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTrackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return (bool) $this->user()?->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tracks,slug',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:100',
            'year' => 'nullable|string|max:4',
            'duration' => 'nullable|string|max:5',
            'thumbnail' => 'nullable|image|max:2048',
            'audio_file' => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/wav|max:15360',
            'is_visible' => 'boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
        ]);
    }
}
