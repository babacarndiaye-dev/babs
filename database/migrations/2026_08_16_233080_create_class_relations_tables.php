<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('inscrit'); // inscrit, transfere, abandonne
            $table->date('enrolled_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'academic_year_id']);
        });

        Schema::create('class_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->decimal('coefficient', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'subject_id', 'academic_year_id'], 'class_subject_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subject_teacher');
        Schema::dropIfExists('student_class');
    }
};
