<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_products', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('supplier_product_code', 120);
            $table->decimal('supplier_price', 18, 2)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->unique(['supplier_id', 'product_id']);
            $table->unique(['supplier_id', 'supplier_product_code'], 'supplier_sku_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_products');
    }
};
