<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_id',
        'client_id',
        'name',
        'email',
        'phone',
        'company',
        'service_type',
        'message',
        'source',
        'status',
        'priority',
        'estimated_value',
        'assigned_to',
        'contacted_at',
        'converted_at',
        'meta',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'contacted_at' => 'datetime',
        'converted_at' => 'datetime',
        'meta' => 'array',
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

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->hasMany(LeadActivity::class)->latest('activity_at');
    }

    // ─── Scopes ───

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'won');
    }

    public function scopeByService($query, string $service)
    {
        return $query->where('service_type', $service);
    }

    // ─── Helpers ───

    public function convertToClient(): Client
    {
        $client = Client::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company' => $this->company,
            'status' => 'active',
        ]);

        $this->update([
            'client_id' => $client->id,
            'status' => 'won',
            'converted_at' => now(),
        ]);

        return $client;
    }
}