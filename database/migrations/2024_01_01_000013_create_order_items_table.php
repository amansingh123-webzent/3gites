<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            // product_id nullable: product may be deleted after purchase (preserve order history)
            $table->foreignId('product_id')->nullable()->constrained('store_products')->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('price', 8, 2); // Snapshot of price at time of purchase
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
