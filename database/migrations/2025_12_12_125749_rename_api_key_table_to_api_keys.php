<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::rename('api_key', 'api_keys');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('api_keys', 'api_key');
    }
};
