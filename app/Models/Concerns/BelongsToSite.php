<?php

namespace App\Models\Concerns;

use App\Models\Site;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToSite
{
    protected static function bootBelongsToSite(): void
    {
        static::addGlobalScope('site', function (Builder $builder) {
            if (app()->bound('currentSite')) {
                $builder->where('site_id', app('currentSite')->id);
            }
        });

        static::creating(function ($model) {
            if (! $model->site_id && app()->bound('currentSite')) {
                $model->site_id = app('currentSite')->id;
            }
        });
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeAllSites(Builder $query): Builder
    {
        return $query->withoutGlobalScope('site');
    }

    public function scopeForSite(Builder $query, int $siteId): Builder
    {
        return $query->withoutGlobalScope('site')->where('site_id', $siteId);
    }
}