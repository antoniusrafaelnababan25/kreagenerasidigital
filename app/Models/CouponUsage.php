<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_id',
        'client_id',
        'usable_id',
        'usable_type',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    // ─── Relationships ───

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function usable()
    {
        return $this->morphTo();
    }
}