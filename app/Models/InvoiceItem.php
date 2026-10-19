<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'description',
        'detail',
        'quantity',
        'unit',
        'unit_price',
        'subtotal',
        'order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ─── Relationships ───

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // ─── Events ───

    protected static function booted(): void
    {
        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });

        static::saved(function ($item) {
            $item->invoice->recalculate();
        });

        static::deleted(function ($item) {
            $item->invoice->recalculate();
        });
    }
}