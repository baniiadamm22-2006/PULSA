<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->string('gateway', 32)->default('midtrans');
            $table->string('gateway_reference', 128)->nullable();
            $table->string('gateway_transaction_id', 128)->nullable();
            $table->string('payment_method', 64)->nullable();
            $table->decimal('amount', 18, 2);
            $table->string('status', 32)->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->unique(['gateway', 'gateway_reference']);
            $table->unique(['gateway', 'gateway_transaction_id'], 'payment_gateway_txn_unique');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
