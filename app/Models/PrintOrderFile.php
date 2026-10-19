<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintOrderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_order_id',
        'file_path',
        'file_name',
        'file_type',
        'mime_type',
        'file_size',
        'version',
        'is_approved',
        'notes',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    // ─── Relationships ───

    public function order()
    {
        return $this->belongsTo(PrintOrder::class, 'print_order_id');
    }

    // ─── Helpers ───

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size ?? 0;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }
}