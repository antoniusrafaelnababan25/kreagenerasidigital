<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

class User extends Authenticatable implements FilamentUser, HasTenants
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ─── Filament User ───

    /**
     * Cek apakah user bisa akses panel admin.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Untuk sekarang, semua user yang login bisa akses.
        // Nanti bisa dibatasi: return $this->hasRole('admin');
        return true;
    }

    // ─── Filament Multi-Tenancy ───

    /**
     * Daftar tenant (site) yang bisa diakses user.
     * Untuk sekarang, semua site bisa diakses.
     */
    public function getTenants(Panel $panel): Collection
    {
        return Site::all();
    }

    /**
     * Cek apakah user bisa akses tenant tertentu.
     */
    public function canAccessTenant(Model $tenant): bool
    {
        // Untuk sekarang, semua site bisa diakses.
        return true;
    }

    // ─── Relationships ───

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'assigned_to');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }
}