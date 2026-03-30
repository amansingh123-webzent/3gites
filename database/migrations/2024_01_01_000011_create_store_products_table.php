<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('is_active')->default(true); // Admin can deactivate without deleting
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_products');
    }
};
