<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'site_id',
        'client_id',
        'project_id',
        'invoicable_id',
        'invoicable_type',
        'title',
        'description',
        'issue_date',
        'due_date',
        'paid_at',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'remaining',
        'status',
        'notes',
        'terms',
        'pdf_path',
        'sent_at',
        'viewed_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining' => 'decimal:2',
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function invoicable()
    {
        return $this->morphTo();
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('order');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // ─── Scopes ───

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->whereIn('status', ['sent', 'viewed']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['paid', 'cancelled']);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['sent', 'viewed', 'partial', 'overdue']);
    }

    // ─── Helpers ───

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV-' . date('Ym');
        $last = static::where('invoice_number', 'like', $prefix . '%')
                      ->orderBy('id', 'desc')
                      ->first();

        $number = $last
            ? (int) substr($last->invoice_number, -4) + 1
            : 1;

        return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items->sum('subtotal');
        $total = $subtotal - $this->discount + $this->tax;
        $paid = $this->payments()->where('status', 'verified')->sum('amount');

        $this->update([
            'subtotal' => $subtotal,
            'total' => $total,
            'paid_amount' => $paid,
            'remaining' => $total - $paid,
        ]);

        // Auto-update status
        if ($paid >= $total && $total > 0) {
            $this->update(['status' => 'paid', 'paid_at' => now()]);
        } elseif ($paid > 0) {
            $this->update(['status' => 'partial']);
        } elseif ($this->due_date->isPast()) {
            $this->update(['status' => 'overdue']);
        }
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && ! $this->isPaid();
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsViewed(): void
    {
        if (! $this->viewed_at) {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }
    }
}