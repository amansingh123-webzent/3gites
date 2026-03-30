<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Paths stored relative to storage/app/public/
            $table->string('teen_photo')->nullable();   // circa 1975, uploadable by admin or member
            $table->string('recent_photo')->nullable(); // self-uploaded by member
            $table->text('bio')->nullable();
            $table->text('career')->nullable();
            $table->text('family_info')->nullable();
            $table->text('retirement_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
