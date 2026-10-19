<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'site_id',
        'client_id',
        'print_product_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'size',
        'color',
        'material',
        'print_technique',
        'quantity',
        'unit_price',
        'subtotal',
        'discount',
        'shipping_cost',
        'total',
        'paid_amount',
        'status',
        'payment_status',
        'shipping_method',
        'tracking_number',
        'approved_at',
        'completed_at',
        'notes',
        'design_notes',
        'handled_by',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'approved_at' => 'datetime',
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

    public function printProduct()
    {
        return $this->belongsTo(PrintProduct::class);
    }

    public function files()
    {
        return $this->hasMany(PrintOrderFile::class);
    }

    public function designFiles()
    {
        return $this->hasMany(PrintOrderFile::class)->where('file_type', 'design');
    }

    public function finalFiles()
    {
        return $this->hasMany(PrintOrderFile::class)->where('file_type', 'final');
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

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProduction($query)
    {
        return $query->whereIn('status', ['approved', 'in_production']);
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
        return 'PRT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getRemainingPaymentAttribute(): float
    {
        return $this->total - $this->paid_amount;
    }

    public function isPaid(): bool
    {
        return $this->paid_amount >= $this->total;
    }

    public function isApproved(): bool
    {
        return $this->approved_at !== null;
    }

    public function canStartProduction(): bool
    {
        return $this->isApproved() && $this->payment_status !== 'unpaid';
    }
}