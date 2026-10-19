<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalBookingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_booking_id',
        'rental_item_id',
        'quantity',
        'price_per_day',
        'days',
        'subtotal',
        'condition_out',
        'condition_in',
        'notes',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ─── Relationships ───

    public function booking()
    {
        return $this->belongsTo(RentalBooking::class, 'rental_booking_id');
    }

    public function rentalItem()
    {
        return $this->belongsTo(RentalItem::class);
    }

    // ─── Helpers ───

    public function hasDamage(): bool
    {
        return $this->condition_in && $this->condition_out
            && $this->condition_in !== $this->condition_out;
    }
}