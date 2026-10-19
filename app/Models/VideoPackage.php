<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'slug',
        'name',
        'category',
        'description',
        'features',
        'price',
        'compare_price',
        'duration',
        'crew_count',
        'output_format',
        'revision_count',
        'delivery_days',
        'featured_image',
        'is_featured',
        'is_active',
        'order',
        'meta',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'meta' => 'array',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function orders()
    {
        return $this->hasMany(VideoOrder::class);
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

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ─── Helpers ───

    public function getDiscountPercentAttribute(): ?float
    {
        if (! $this->compare_price || $this->compare_price <= 0) {
            return null;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100, 2);
    }

    public function getFeaturesArrayAttribute(): array
    {
        if (is_array($this->features)) {
            return $this->features;
        }

        if (is_string($this->features)) {
            return array_filter(array_map('trim', explode("\n", $this->features)));
        }

        return [];
    }
}