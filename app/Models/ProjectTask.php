<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'parent_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'order',
        'due_date',
        'completed_at',
        'estimated_hours',
        'actual_hours',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
    ];

    // ─── Relationships ───

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(ProjectTask::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProjectTask::class, 'parent_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    // ─── Scopes ───

    public function scopeTodo($query)
    {
        return $query->where('status', 'todo');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['done', 'cancelled']);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // ─── Helpers ───

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && ! $this->isDone();
    }

    public function markAsDone(): void
    {
        $this->update([
            'status' => 'done',
            'completed_at' => now(),
        ]);
    }
}