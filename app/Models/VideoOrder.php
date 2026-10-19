<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'site_id',
        'client_id',
        'video_package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'project_title',
        'video_type',
        'concept',
        'reference',
        'location',
        'shooting_date',
        'shooting_time',
        'duration_minutes',
        'subtotal',
        'discount',
        'total',
        'paid_amount',
        'status',
        'payment_status',
        'confirmed_at',
        'completed_at',
        'notes',
        'handled_by',
    ];

    protected $casts = [
        'shooting_date' => 'date',
        'shooting_time' => 'datetime:H:i',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function videoPackage()
    {
        return $this->belongsTo(VideoPackage::class);
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

    public function scopeInProduction($query)
    {
        return $query->whereIn('status', ['pre_production', 'shooting', 'editing']);
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
        return 'VID-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getRemainingPaymentAttribute(): float
    {
        return $this->total - $this->paid_amount;
    }

    public function isPaid(): bool
    {
        return $this->paid_amount >= $this->total;
    }

    public function getProductionStageLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'pre_production' => 'Pra-Produksi',
            'shooting' => 'Pengambilan Gambar',
            'editing' => 'Editing',
            'review' => 'Review Klien',
            'revision' => 'Revisi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}