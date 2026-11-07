<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_contact',
        'customer_name',
        'total_amount',
        'status',
        'tracking_token_hash',
        'payment_expires_at',
    ];

    protected $hidden = [
        'tracking_token_hash',
        'customer_contact',
        'customer_name',
    ];

    protected function casts(): array
    {
        return [
            'customer_contact' => 'encrypted',
            'customer_name' => 'encrypted',
            'total_amount' => 'decimal:2',
            'payment_expires_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }
}
