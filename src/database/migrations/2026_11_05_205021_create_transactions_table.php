<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('order_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('local_reference', 128)->unique();
            $table->string('supplier_reference', 128)->nullable();
            $table->text('destination');
            $table->text('result_reference')->nullable();
            $table->string('status', 32)->default('pending');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            $table->unique(['supplier_id', 'supplier_reference'], 'supplier_reference_unique');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
