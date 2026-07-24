<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'source_platform',
        'lead_time_days',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'lead_time_days' => 'integer',
        ];
    }

    public function supplierProducts(): HasMany
    {
        return $this->hasMany(SupplierProduct::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'supplier_products')
            ->withPivot(['moq', 'unit_cost'])
            ->withTimestamps();
    }

    public function purchaseRequests(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getSourcePlatformLabelAttribute(): string
    {
        return match ($this->source_platform) {
            'alibaba'       => 'Alibaba',
            'local_factory' => 'Local Factory',
            default         => 'Other',
        };
    }
}
