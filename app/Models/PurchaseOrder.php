<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_request_id',
        'po_number',
        'quantity_ordered',
        'unit_cost',
        'total_cost',
        'expected_arrival_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity_ordered'     => 'integer',
            'unit_cost'            => 'decimal:2',
            'total_cost'           => 'decimal:2',
            'expected_arrival_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PurchaseOrder $po) {
            if (! $po->po_number) {
                $po->po_number = 'PO-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function purchaseRequest(): BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class);
    }

    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    public function getTotalReceivedQtyAttribute(): int
    {
        return $this->goodsReceipts->sum('quantity_received');
    }

    public function getRemainingQtyAttribute(): int
    {
        return max(0, $this->quantity_ordered - $this->total_received_qty);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'ordered'            => 'Ordered',
            'partially_received' => 'Partially Received',
            'received'           => 'Fully Received',
            'cancelled'          => 'Cancelled',
            default              => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'ordered'            => 'blue',
            'partially_received' => 'amber',
            'received'           => 'green',
            'cancelled'          => 'red',
            default              => 'gray',
        };
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['ordered', 'partially_received']);
    }
}
