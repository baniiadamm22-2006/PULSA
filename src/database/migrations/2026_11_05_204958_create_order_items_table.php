<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_code_snapshot', 100);
            $table->string('product_name_snapshot', 200);
            $table->text('destination_snapshot');
            $table->json('product_attributes_snapshot')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('cost_price_snapshot', 18, 2);
            $table->decimal('selling_price_snapshot', 18, 2);
            $table->decimal('total_amount', 18, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
