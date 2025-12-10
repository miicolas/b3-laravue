<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Track extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'artist',
        'album',
        'genre',
        'year',
        'duration',
        'thumbnail',
        'audio_file',
        'is_visible',
    ];

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)->withPivot('play_count')->withTimestamps();
    }

    protected $appends = [
        'thumbnail_url',
        'audio_file_url',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        return Storage::disk('public')->url($this->thumbnail);
    }

    public function getAudioFileUrlAttribute(): ?string
    {
        if (! $this->audio_file) {
            return null;
        }

        return Storage::disk('public')->url($this->audio_file);
    }

    public function searchTracks($search, $isVisible = true)
    {
        return $this->where('title', 'like', "%{$search}%")->where('is_visible', $isVisible)->get();
    }
}
