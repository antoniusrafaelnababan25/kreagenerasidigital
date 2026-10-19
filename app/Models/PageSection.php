<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory, BelongsToSite;

    protected $fillable = [
        'page_id',
        'site_id',
        'type',
        'order',
        'content',
        'is_active',
    ];

    protected $casts = [
        'content'   => 'array',
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}