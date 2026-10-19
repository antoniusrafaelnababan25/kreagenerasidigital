<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'site_id',
        'client_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'subtotal',
        'discount',
        'tax',
        'shipping_cost',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'notes',
        'paid_at',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoicable');
    }

    public function couponUsages()
    {
        return $this->morphMany(CouponUsage::class, 'usable');
    }

    // ─── Scopes ───

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ─── Helpers ───

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->payments()->where('status', 'verified')->sum('amount');
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}