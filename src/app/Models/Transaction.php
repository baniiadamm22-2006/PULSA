<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'supplier_id',
        'local_reference',
        'supplier_reference',
        'destination',
        'result_reference',
        'status',
        'last_checked_at',
    ];

    protected $hidden = [
        'destination',
        'result_reference',
    ];

    protected function casts(): array
    {
        return [
            'destination' => 'encrypted',
            'result_reference' => 'encrypted',
            'last_checked_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    public function logs(): HasMany
    {
        return $this->hasMany(TransactionLog::class);
    }
}
