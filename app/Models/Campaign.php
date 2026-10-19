<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'name',
        'subject',
        'content',
        'type',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'total_opened',
        'total_clicked',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // ─── Helpers ───

    public function openRate(): float
    {
        return $this->total_recipients > 0
            ? round(($this->total_opened / $this->total_recipients) * 100, 2)
            : 0;
    }

    public function clickRate(): float
    {
        return $this->total_recipients > 0
            ? round(($this->total_clicked / $this->total_recipients) * 100, 2)
            : 0;
    }
}