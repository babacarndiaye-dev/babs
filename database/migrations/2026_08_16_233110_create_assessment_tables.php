<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->string('type')->default('devoir'); // devoir, interrogation, examen, pratique, projet, continu, stage
            $table->string('title');
            $table->date('date')->nullable();
            $table->decimal('max_score', 6, 2)->default(20);
            $table->decimal('coefficient', 5, 2)->default(1);
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 6, 2)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'student_id']);
        });

        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->string('period'); // ex: Trimestre 1
            $table->decimal('general_average', 6, 2)->nullable();
            $table->unsignedInteger('rank')->nullable();
            $table->unsignedInteger('class_size')->nullable();
            $table->string('decision')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'class_id', 'academic_year_id', 'period'], 'report_card_unique');
        });

        Schema::create('report_card_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_card_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->decimal('average', 6, 2)->nullable();
            $table->decimal('coefficient', 5, 2)->nullable();
            $table->text('teacher_comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_card_lines');
        Schema::dropIfExists('report_cards');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('assessments');
    }
};
