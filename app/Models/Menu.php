<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, BelongsToSite;

    protected $fillable = [
        'site_id',
        'parent_id',
        'label',
        'url',
        'icon',
        'order',
        'is_active',
        'target_blank',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_blank' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    public function isActive(): bool
    {
        $currentUrl = url()->current();
        $menuUrl = url($this->url);

        if ($currentUrl === $menuUrl) {
            return true;
        }

        if ($this->url !== '/' && str_starts_with($currentUrl, $menuUrl)) {
            return true;
        }

        return false;
    }
}