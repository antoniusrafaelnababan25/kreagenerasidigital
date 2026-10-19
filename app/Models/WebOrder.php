<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'site_id',
        'client_id',
        'web_package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company_name',
        'business_type',
        'project_title',
        'description',
        'reference_websites',
        'requested_features',
        'domain_preference',
        'needs_hosting',
        'needs_content',
        'subtotal',
        'discount',
        'total',
        'paid_amount',
        'status',
        'payment_status',
        'confirmed_at',
        'launched_at',
        'live_url',
        'notes',
        'handled_by',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'launched_at' => 'datetime',
        'requested_features' => 'array',
        'needs_hosting' => 'boolean',
        'needs_content' => 'boolean',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
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

    public function webPackage()
    {
        return $this->belongsTo(WebPackage::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function invoice()
    {
        return $this->morphOne(Invoice::class, 'invoicable');
    }

    public function project()
    {
        return $this->morphOne(Project::class, 'projectable');
    }

    // ─── Scopes ───

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->whereIn('status', ['design', 'development', 'review', 'revision']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    // ─── Helpers ───

    public static function generateOrderNumber(): string
    {
        return 'WEB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getRemainingPaymentAttribute(): float
    {
        return $this->total - $this->paid_amount;
    }

    public function isPaid(): bool
    {
        return $this->paid_amount >= $this->total;
    }

    public function isLaunched(): bool
    {
        return $this->launched_at !== null;
    }

    public function getProgressPercentAttribute(): int
    {
        return match ($this->status) {
            'pending' => 0,
            'brief_confirmed' => 15,
            'design' => 35,
            'development' => 60,
            'review' => 80,
            'revision' => 85,
            'completed' => 100,
            default => 0,
        };
    }
}