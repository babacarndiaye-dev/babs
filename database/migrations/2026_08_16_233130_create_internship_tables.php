<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sector')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('company_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('internships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('company_id')->constrained()->restrictOnDelete();
            $table->foreignId('supervisor_contact_id')->nullable()->constrained('company_contacts')->nullOnDelete();
            $table->foreignId('formation_id')->nullable()->constrained()->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('agreement_path')->nullable();
            $table->string('report_path')->nullable();
            $table->string('status')->default('planifie'); // planifie, en_cours, termine, evalue
            $table->timestamps();
        });

        Schema::create('internship_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('evaluator_name')->nullable();
            $table->decimal('score', 6, 2)->nullable();
            $table->text('comments')->nullable();
            $table->date('evaluated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internship_evaluations');
        Schema::dropIfExists('internships');
        Schema::dropIfExists('company_contacts');
        Schema::dropIfExists('companies');
    }
};
