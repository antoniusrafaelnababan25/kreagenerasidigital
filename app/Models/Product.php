<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'category_id',
        'slug',
        'name',
        'description',
        'short_description',
        'price',
        'compare_price',
        'cost_price',
        'stock',
        'sku',
        'weight',
        'unit',
        'status',
        'is_featured',
        'track_stock',
        'meta',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'is_featured' => 'boolean',
        'track_stock' => 'boolean',
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

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─── Scopes ───

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ─── Helpers ───

    public function getDiscountPercentAttribute(): ?float
    {
        if (! $this->compare_price || $this->compare_price <= 0) {
            return null;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 2);
    }

    public function isInStock(): bool
    {
        return ! $this->track_stock || $this->stock > 0;
    }
}