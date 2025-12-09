<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence,
            'slug' => Str::uuid(),
            'artist' => fake()->name(),
            'album' => fake()->sentence(),
            'genre' => fake()->word(),
            'year' => fake()->year(),
            'duration' => fake()->time(),
            'is_visible' => fake()->boolean(),
            'play_count' => fake()->numberBetween(0, 1000),
            'audio_file' => 'audio/'.Str::uuid().'.mp3',
            'thumbnail' => fake()->imageUrl(),
        ];
    }
}
