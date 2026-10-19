<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, BelongsToSite;

    protected $fillable = [
        'site_id',
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('order');
    }
}