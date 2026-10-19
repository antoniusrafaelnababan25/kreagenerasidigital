<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_number',
        'site_id',
        'client_id',
        'lead_id',
        'projectable_id',
        'projectable_type',
        'name',
        'description',
        'service_type',
        'status',
        'priority',
        'start_date',
        'deadline',
        'completed_at',
        'budget',
        'actual_cost',
        'billed_amount',
        'progress',
        'manager_id',
        'team_members',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'deadline' => 'date',
        'completed_at' => 'datetime',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'billed_amount' => 'decimal:2',
        'team_members' => 'array',
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

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function projectable()
    {
        return $this->morphTo();
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class)->orderBy('order');
    }

    public function rootTasks()
    {
        return $this->hasMany(ProjectTask::class)->whereNull('parent_id')->orderBy('order');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // ─── Scopes ───

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'in_progress', 'review']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByService($query, string $service)
    {
        return $query->where('service_type', $service);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // ─── Helpers ───

    public static function generateProjectNumber(): string
    {
        return 'PRJ-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function getProfitAttribute(): float
    {
        return $this->billed_amount - $this->actual_cost;
    }

    public function getProfitMarginAttribute(): float
    {
        return $this->billed_amount > 0
            ? round(($this->profit / $this->billed_amount) * 100, 2)
            : 0;
    }

    public function getTotalHoursAttribute(): float
    {
        return $this->timesheets->sum('hours');
    }

    public function getBillableHoursAttribute(): float
    {
        return $this->timesheets->where('is_billable', true)->sum('hours');
    }

    public function getTaskProgressAttribute(): int
    {
        $total = $this->tasks->count();
        if ($total === 0) return 0;

        $done = $this->tasks->where('status', 'done')->count();
        return (int) round(($done / $total) * 100);
    }

    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline->isPast()
            && ! in_array($this->status, ['completed', 'cancelled']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Sedang Berjalan',
            'review' => 'Review',
            'completed' => 'Selesai',
            'on_hold' => 'Ditahan',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}