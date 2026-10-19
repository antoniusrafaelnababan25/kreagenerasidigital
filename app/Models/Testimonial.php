<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Testimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'client_id',
        'name',
        'position',
        'company',
        'avatar',
        'content',
        'rating',
        'service_type',
        'project_reference',
        'is_featured',
        'is_published',
        'order',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
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

    public function scopeHighRating($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // ─── Helpers ───

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        $initials = '';

        foreach ($words as $word) {
            if (! empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    public function getStarDisplayAttribute(): string
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }
}