<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'address',
        'city',
        'province',
        'postal_code',
        'avatar',
        'status',
        'notes',
    ];

    // ─── Relationships ───

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function rentalBookings()
    {
        return $this->hasMany(RentalBooking::class);
    }

    public function printOrders()
    {
        return $this->hasMany(PrintOrder::class);
    }

    public function videoOrders()
    {
        return $this->hasMany(VideoOrder::class);
    }

    public function webOrders()
    {
        return $this->hasMany(WebOrder::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVip($query)
    {
        return $query->where('status', 'vip');
    }
}