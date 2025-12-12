<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiKey extends Model
{
    protected $table = 'api_keys';

    protected $fillable = [
        'slug',
        'user_id',
        'name',
        'key',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function generateKey(): string
    {
        return Str::random(40);
    }

    public static function generateRandomKey(): string
    {
        return Str::random(40);
    }
}
