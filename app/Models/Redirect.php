<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'hit_count',
        'last_hit_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_hit_at' => 'datetime',
    ];
}