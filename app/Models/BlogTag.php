<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasFactory, BelongsToSite;

    protected $fillable = [
        'site_id',
        'slug',
        'name',
    ];

    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tag');
    }
}