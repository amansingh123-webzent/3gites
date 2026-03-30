<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tributes', function (Blueprint $table) {
            $table->id();
            // FK to users: the deceased member's user record (nullable for safety)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_name');          // Display name (may differ from user.name)
            $table->smallInteger('birth_year')->nullable();
            $table->smallInteger('death_year')->nullable();
            $table->text('tribute_text');           // Max 250 words enforced at application layer
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tributes');
    }
};
