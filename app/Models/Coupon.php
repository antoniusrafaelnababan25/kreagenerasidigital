<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'usage_count',
        'usage_limit_per_user',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where(function ($q) {
                         $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
                     })
                     ->where(function ($q) {
                         $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
                     });
    }

    // ─── Helpers ───

    public function isValid(): bool
    {
        if (! $this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->min_purchase && $subtotal < $this->min_purchase) {
            return 0;
        }

        $discount = $this->type === 'percentage'
            ? $subtotal * ($this->value / 100)
            : $this->value;

        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        return min($discount, $subtotal);
    }
}