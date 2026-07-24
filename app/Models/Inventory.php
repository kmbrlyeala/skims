<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'on_hand_qty',
        'incoming_qty',
        'reorder_point',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'on_hand_qty'    => 'integer',
            'incoming_qty'   => 'integer',
            'reorder_point'  => 'integer',
            'last_updated_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Increase on-hand stock and decrease incoming. Called ONLY from ProcessGoodsReceipt action.
     */
    public function incrementStock(int $receivedQty): void
    {
        $this->on_hand_qty    = max(0, $this->on_hand_qty + $receivedQty);
        $this->incoming_qty   = max(0, $this->incoming_qty - $receivedQty);
        $this->last_updated_at = now();
        $this->save();
    }

    /**
     * Add to incoming qty when a PO is created (approved PR).
     */
    public function addIncoming(int $qty): void
    {
        $this->increment('incoming_qty', $qty);
    }

    /**
     * Remove from incoming qty (e.g., PO cancelled).
     */
    public function removeIncoming(int $qty): void
    {
        $this->incoming_qty = max(0, $this->incoming_qty - $qty);
        $this->save();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->on_hand_qty <= $this->reorder_point && $this->reorder_point > 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return $this->on_hand_qty === 0;
    }
}
