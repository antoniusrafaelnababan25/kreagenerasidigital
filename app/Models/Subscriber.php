<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'email',
        'name',
        'status',
        'source',
        'subscribed_at',
        'unsubscribed_at',
        'token',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}