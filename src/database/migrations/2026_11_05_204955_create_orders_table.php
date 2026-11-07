<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('invoice_number', 80)->unique();
            $table->text('customer_contact');
            $table->text('customer_name')->nullable();
            $table->decimal('total_amount', 18, 2);
            $table->string('status', 32)->default('awaiting_payment');
            $table->string('tracking_token_hash', 64)->nullable()->unique();
            $table->timestamp('payment_expires_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
