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
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropForeign(['playlist_id']);
            $table->dropColumn('playlist_id');
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('playlists', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->string('user_id')->change();
        });

        Schema::table('tracks', function (Blueprint $table) {
            $table->foreignId('playlist_id')->nullable()->after('id')->constrained('playlists');
        });
    }
};
