<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'project_task_id',
        'date',
        'started_at',
        'ended_at',
        'hours',
        'description',
        'is_billable',
        'hourly_rate',
        'is_approved',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'hours' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_approved' => 'boolean',
    ];

    // ─── Relationships ───

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(ProjectTask::class, 'project_task_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ─── Scopes ───

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    // ─── Helpers ───

    public function getBillableAmountAttribute(): float
    {
        return $this->is_billable && $this->hourly_rate
            ? $this->hours * $this->hourly_rate
            : 0;
    }

    public function approve(int $userId): void
    {
        $this->update([
            'is_approved' => true,
            'approved_by' => $userId,
        ]);
    }
}