<?php

namespace Tests\Feature;

use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TracksAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_users_cannot_manage_tracks(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $track = Track::factory()->create();

        $this->actingAs($user);

        $this->get('/tracks/create')->assertForbidden();
        $this->post('/tracks', [
            'title' => 'Titre protégé',
            'artist' => 'Artiste',
            'is_visible' => true,
        ])->assertForbidden();
        $this->get("/tracks/{$track->slug}/edit")->assertForbidden();
        $this->put("/tracks/{$track->slug}", [
            'title' => 'Titre modifié',
            'artist' => 'Artiste',
            'is_visible' => true,
        ])->assertForbidden();
        $this->delete("/tracks/{$track->slug}")->assertForbidden();
    }

    public function test_admin_can_create_update_and_delete_tracks(): void
    {
        $admin = User::factory()->admin()->create();
        $track = Track::factory()->create();

        $this->actingAs($admin);

        $createResponse = $this->post('/tracks', [
            'title' => 'Nouveau titre',
            'artist' => 'Nouvel artiste',
            'album' => 'Nouvel album',
            'genre' => 'Genre',
            'year' => '2024',
            'duration' => '03:30',
            'is_visible' => true,
        ]);

        $createResponse->assertRedirect(route('tracks.index'));
        $this->assertDatabaseHas('tracks', [
            'title' => 'Nouveau titre',
            'artist' => 'Nouvel artiste',
        ]);

        $updateResponse = $this->put("/tracks/{$track->slug}", [
            'title' => 'Titre mis à jour',
            'artist' => 'Artiste mis à jour',
            'album' => 'Album mis à jour',
            'genre' => 'Genre mis à jour',
            'year' => '2025',
            'duration' => '04:00',
            'is_visible' => false,
        ]);

        $updateResponse->assertRedirect(route('tracks.index'));
        $this->assertDatabaseHas('tracks', [
            'id' => $track->id,
            'title' => 'Titre mis à jour',
            'artist' => 'Artiste mis à jour',
        ]);

        $deleteResponse = $this->delete("/tracks/{$track->fresh()->slug}");
        $deleteResponse->assertRedirect(route('tracks.index'));
        $this->assertDatabaseMissing('tracks', [
            'id' => $track->id,
        ]);
    }
}
