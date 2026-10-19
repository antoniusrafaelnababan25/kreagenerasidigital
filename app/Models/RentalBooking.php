<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'site_id',
        'client_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_id_number',
        'customer_id_photo',
        'start_date',
        'end_date',
        'total_days',
        'pickup_method',
        'pickup_address',
        'picked_up_at',
        'returned_at',
        'subtotal',
        'discount',
        'deposit',
        'late_fee',
        'damage_fee',
        'total',
        'paid_amount',
        'refund_amount',
        'status',
        'payment_status',
        'notes',
        'return_notes',
        'handled_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'picked_up_at' => 'datetime',
        'returned_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'deposit' => 'decimal:2',
        'late_fee' => 'decimal:2',
        'damage_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
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
        return $this->hasMany(RentalBookingItem::class);
    }

    public function rentalItems()
    {
        return $this->belongsToMany(RentalItem::class, 'rental_booking_items')
                    ->withPivot('quantity', 'price_per_day', 'days', 'subtotal')
                    ->withTimestamps();
    }

    public function returns()
    {
        return $this->hasMany(RentalReturn::class);
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

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'picked_up']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'picked_up')
                     ->where('end_date', '<', now());
    }

    // ─── Helpers ───

    public static function generateBookingNumber(): string
    {
        return 'RNT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getRemainingPaymentAttribute(): float
    {
        return $this->total - $this->paid_amount;
    }

    public function isPaid(): bool
    {
        return $this->paid_amount >= $this->total;
    }

    public function calculateLateFee(int $daysLate): float
    {
        // Denda 20% dari harga sewa per hari keterlambatan
        $dailyRate = $this->subtotal / max($this->total_days, 1);
        return $dailyRate * $daysLate * 1.2;
    }
}