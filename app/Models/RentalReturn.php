<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_booking_id',
        'handled_by',
        'returned_at',
        'days_late',
        'late_fee',
        'damage_fee',
        'deposit_returned',
        'condition_notes',
        'item_conditions',
        'status',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'deposit_returned' => 'decimal:2',
        'item_conditions' => 'array',
    ];

    // ─── Relationships ───

    public function booking()
    {
        return $this->belongsTo(RentalBooking::class, 'rental_booking_id');
    }

    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    // ─── Helpers ───

    public function getTotalDeductionAttribute(): float
    {
        return $this->late_fee + $this->damage_fee;
    }

    public function hasIssues(): bool
    {
        return $this->days_late > 0 || $this->damage_fee > 0;
    }
}