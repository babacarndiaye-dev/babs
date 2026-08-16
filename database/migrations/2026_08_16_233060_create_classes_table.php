<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('formation_id')->constrained()->restrictOnDelete();
            $table->foreignId('specialty_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('capacity')->nullable();
            $table->timestamps();

            $table->unique(['name', 'academic_year_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
