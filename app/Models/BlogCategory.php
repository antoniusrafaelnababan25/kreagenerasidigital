<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory, BelongsToSite;

    protected $fillable = [
        'site_id',
        'parent_id',
        'slug',
        'name',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->orderBy('order');
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class);
    }
}