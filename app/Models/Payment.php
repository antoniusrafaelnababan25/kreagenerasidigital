<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'payment_number',
        'amount',
        'method',
        'status',
        'reference',
        'proof_path',
        'notes',
        'paid_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ─── Relationships ───

    public function payable()
    {
        return $this->morphTo();
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ─── Scopes ───

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ─── Helpers ───

    public static function generatePaymentNumber(): string
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}