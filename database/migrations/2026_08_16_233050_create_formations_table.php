<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('domain_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('objectives')->nullable();
            $table->longText('curriculum')->nullable();
            $table->longText('skills')->nullable();
            $table->longText('career_opportunities')->nullable();
            $table->longText('admission_requirements')->nullable();
            $table->unsignedInteger('duration_value')->nullable();
            $table->string('duration_unit')->nullable(); // mois, ans
            $table->string('level_label')->nullable();
            $table->decimal('tuition_fee', 12, 2)->nullable();
            $table->unsignedInteger('seats_available')->nullable();
            $table->date('start_date')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('formation_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('coefficient', 5, 2)->default(1);
            $table->unsignedInteger('hours_per_week')->nullable();
            $table->timestamps();

            $table->unique(['formation_id', 'subject_id', 'level_id'], 'formation_subject_level_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formation_subjects');
        Schema::dropIfExists('specialties');
        Schema::dropIfExists('formations');
    }
};
