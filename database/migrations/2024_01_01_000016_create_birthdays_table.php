<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('birthdays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('birth_month');  // 1–12
            $table->tinyInteger('birth_day');    // 1–31
            // Birth year is optional — stored privately, never shown publicly
            $table->smallInteger('birth_year')->nullable();
            $table->timestamps();
            $table->unique('user_id'); // One birthday record per member
            // Index for the daily birthday check job
            $table->index(['birth_month', 'birth_day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('birthdays');
    }
};
