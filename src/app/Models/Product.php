<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'provider_id',
        'code',
        'name',
        'description',
        'cost_price',
        'selling_price',
        'quota',
        'validity_days',
        'countries',
        'terms',
        'attributes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'validity_days' => 'integer',
            'countries' => 'array',
            'attributes' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
    public function supplierProducts(): HasMany
    {
        return $this->hasMany(SupplierProduct::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected static function booted(): void
    {
        static::updated(function (self $product): void {
            if (! $product->wasChanged(['cost_price', 'selling_price'])) {
                return;
            }

            AdminActivityLog::query()->create([
                'user_id' => auth()->id(),
                'action' => 'product.price_changed',
                'subject_type' => self::class,
                'subject_id' => $product->getKey(),
                'before_state' => [
                    'cost_price' => $product->getRawOriginal('cost_price'),
                    'selling_price' => $product->getRawOriginal('selling_price'),
                ],
                'after_state' => [
                    'cost_price' => $product->cost_price,
                    'selling_price' => $product->selling_price,
                ],
            ]);
        });
    }
}
