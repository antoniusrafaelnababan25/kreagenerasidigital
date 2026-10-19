<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RentalItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'category_id',
        'slug',
        'name',
        'brand',
        'description',
        'specification',
        'price_per_day',
        'price_per_week',
        'deposit',
        'stock_total',
        'stock_available',
        'stock_maintenance',
        'condition',
        'status',
        'featured_image',
        'is_featured',
        'is_active',
        'weight',
        'meta',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'price_per_week' => 'decimal:2',
        'deposit' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function bookingItems()
    {
        return $this->hasMany(RentalBookingItem::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(RentalBooking::class, 'rental_booking_items')
                    ->withPivot('quantity', 'price_per_day', 'days', 'subtotal')
                    ->withTimestamps();
    }

    // ─── Scopes ───

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                     ->where('stock_available', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ─── Helpers ───

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->stock_available > 0;
    }

    public function calculatePrice(int $quantity, int $days): float
    {
        $pricePerDay = $this->price_per_day;

        // Diskon mingguan kalau ada
        if ($days >= 7 && $this->price_per_week) {
            $weeks = floor($days / 7);
            $remainingDays = $days % 7;
            return ($weeks * $this->price_per_week + $remainingDays * $pricePerDay) * $quantity;
        }

        return $pricePerDay * $days * $quantity;
    }

    public function isAvailableForPeriod(string $startDate, string $endDate, int $quantity): bool
    {
        $booked = RentalBookingItem::where('rental_item_id', $this->id)
            ->whereHas('booking', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['confirmed', 'picked_up'])
                  ->where(function ($q) use ($startDate, $endDate) {
                      $q->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('start_date', '<=', $startDate)
                              ->where('end_date', '>=', $endDate);
                        });
                  });
            })
            ->sum('quantity');

        return ($this->stock_total - $booked) >= $quantity;
    }
}