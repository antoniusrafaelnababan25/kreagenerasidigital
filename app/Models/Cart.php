<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'user_id',
        'client_id',
        'session_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ─── Helpers ───

    public function getSubtotalAttribute(): float
    {
        return $this->items->sum(fn ($item) => $item->price * $item->quantity);
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}