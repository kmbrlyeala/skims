<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'quantity_received',
        'quantity_damaged',
        'received_by',
        'received_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_received' => 'integer',
            'quantity_damaged'  => 'integer',
            'received_at'       => 'date',
        ];
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function getNetReceivedAttribute(): int
    {
        return max(0, $this->quantity_received - $this->quantity_damaged);
    }
}
