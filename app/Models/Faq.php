<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'faqs';

    protected $fillable = [
        'site_id',
        'question',
        'answer',
        'category',
        'service_type',
        'is_published',
        'is_featured',
        'order',
        'view_count',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    // ─── Relationships ───

    public function site()
    {
        return $this->belongsTo(Site::class);
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

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ─── Helpers ───

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function toSchemaArray(): array
    {
        return [
            '@type' => 'Question',
            'name' => $this->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags($this->answer),
            ],
        ];
    }
}