<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryBatch extends Model
{
    protected $fillable = ['product_id', 'batch_number', 'quantity', 'expiration_date'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'expiration_date' => 'date',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
