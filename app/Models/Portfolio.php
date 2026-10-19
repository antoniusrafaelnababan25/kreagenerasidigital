<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Portfolio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'category_id',
        'slug',
        'title',
        'client_name',
        'short_description',
        'description',
        'featured_image',
        'gallery',
        'video_url',
        'live_url',
        'service_type',
        'project_year',
        'tags',
        'meta_title',
        'meta_description',
        'is_featured',
        'is_published',
        'order',
        'view_count',
    ];

    protected $casts = [
        'gallery' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
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

    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    // ─── Scopes ───

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByService($query, string $service)
    {
        return $query->where('service_type', $service);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ─── Helpers ───

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getGalleryImagesAttribute(): array
    {
        return $this->gallery ?? [];
    }

    public function hasVideo(): bool
    {
        return ! empty($this->video_url);
    }

    public function hasLiveUrl(): bool
    {
        return ! empty($this->live_url);
    }
}