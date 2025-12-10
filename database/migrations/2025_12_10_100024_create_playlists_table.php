<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('user_id');
            $table->timestamps();
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->foreignId('playlist_id')->nullable()->after('id')->constrained('playlists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('playlist_id');
        });
    }
};
