<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrintProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'category_id',
        'slug',
        'name',
        'description',
        'type',
        'material',
        'print_technique',
        'base_price',
        'min_order_qty',
        'production_days',
        'featured_image',
        'available_sizes',
        'available_colors',
        'is_featured',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'min_order_qty' => 'decimal:2',
        'available_sizes' => 'array',
        'available_colors' => 'array',
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

    public function orders()
    {
        return $this->hasMany(PrintOrder::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // ─── Helpers ───

    public function calculatePrice(int $quantity): float
    {
        $price = $this->base_price * $quantity;

        // Diskon bertingkat
        if ($quantity >= 100) {
            $price *= 0.85; // diskon 15%
        } elseif ($quantity >= 50) {
            $price *= 0.90; // diskon 10%
        } elseif ($quantity >= 12) {
            $price *= 0.95; // diskon 5%
        }

        return $price;
    }

    public function meetsMinOrder(int $quantity): bool
    {
        return $quantity >= $this->min_order_qty;
    }
}