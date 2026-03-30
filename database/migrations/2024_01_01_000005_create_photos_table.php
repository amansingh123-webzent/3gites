<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // uploader
            $table->string('file_path');
            $table->string('caption', 500)->nullable();
            // true = belongs to admin gallery (500 photo limit)
            // false = belongs to member's personal gallery (50 photo limit per member)
            $table->boolean('is_admin_gallery')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
