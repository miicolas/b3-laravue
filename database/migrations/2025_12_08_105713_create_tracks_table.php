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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('artist');
            $table->string('album')->nullable();
            $table->string('genre')->nullable();
            $table->string('year')->nullable();
            $table->string('duration')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('audio_file')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('play_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
