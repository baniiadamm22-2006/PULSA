<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_code_snapshot',
        'product_name_snapshot',
        'destination_snapshot',
        'product_attributes_snapshot',
        'quantity',
        'cost_price_snapshot',
        'selling_price_snapshot',
        'total_amount',
    ];

    protected $hidden = [
        'destination_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'destination_snapshot' => 'encrypted',
            'product_attributes_snapshot' => 'array',
            'quantity' => 'integer',
            'cost_price_snapshot' => 'decimal:2',
            'selling_price_snapshot' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
