<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'price',
        'photos',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'photos'    => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function inventoryBatches(): HasMany
    {
        return $this->hasMany(InventoryBatch::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function supplierProducts(): HasMany
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'supplier_products')
            ->withPivot(['moq', 'unit_cost'])
            ->withTimestamps();
    }

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    /** Live on-hand stock read directly from inventory — never stored on the listing itself. */
    public function getLiveStockAttribute(): int
    {
        return $this->inventory?->on_hand_qty ?? 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        $inv = $this->inventory;
        if (! $inv) return false;
        return $inv->on_hand_qty <= $inv->reorder_point && $inv->reorder_point > 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return ($this->inventory?->on_hand_qty ?? 0) === 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->is_out_of_stock) return 'out_of_stock';
        if ($this->is_low_stock) return 'low';
        return 'ok';
    }

    public function getPhotoUrlsAttribute(): array
    {
        return collect($this->photos ?? [])
            ->map(fn ($path) => Storage::url($path))
            ->toArray();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->whereHas('inventory', fn ($q) => $q->where('on_hand_qty', '>', 0));
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('inventory', function ($q) {
            $q->whereColumn('on_hand_qty', '<=', 'reorder_point')
              ->where('reorder_point', '>', 0);
        });
    }
}
