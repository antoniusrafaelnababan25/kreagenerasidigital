<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes, BelongsToSite;

    protected $fillable = [
        'site_id',
        'blog_category_id',
        'author_id',
        'slug',
        'title',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
        'reading_time_minutes',
        'view_count',
        'is_featured',
        'allow_comments',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'schema_json',
        'focus_keyword',
        'is_indexable',
    ];

    protected $casts = [
        'published_at'         => 'datetime',
        'is_featured'          => 'boolean',
        'allow_comments'       => 'boolean',
        'is_indexable'         => 'boolean',   // FIX: ada di DB, belum di-cast
        'schema_json'          => 'array',
        'reading_time_minutes' => 'integer',
        'view_count'           => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function seoMeta()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    // ─── Scopes ───

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    public function scopeIndexable($query)
    {
        return $query->where('is_indexable', true);
    }
}