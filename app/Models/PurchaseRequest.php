<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity_requested',
        'unit_cost',
        'expected_delivery_date',
        'status',
        'notes',
        'is_auto_draft',
        'requested_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_requested'    => 'integer',
            'unit_cost'             => 'decimal:2',
            'expected_delivery_date' => 'date',
            'is_auto_draft'         => 'boolean',
            'approved_at'           => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function purchaseOrder(): HasOne
    {
        return $this->hasOne(PurchaseOrder::class);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending_approval');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeAutoDraft($query)
    {
        return $query->where('is_auto_draft', true)->where('status', 'draft');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'              => 'Draft',
            'pending_approval'   => 'Pending Approval',
            'approved'           => 'Approved',
            'rejected'           => 'Rejected',
            'received'           => 'Received',
            'partially_received' => 'Partially Received',
            default              => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft'              => 'gray',
            'pending_approval'   => 'amber',
            'approved'           => 'blue',
            'rejected'           => 'red',
            'received'           => 'green',
            'partially_received' => 'orange',
            default              => 'gray',
        };
    }
}
