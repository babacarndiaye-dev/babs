<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('formation_id')->constrained()->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->string('status')->default('nouveau');
            // nouveau, en_etude, incomplet, pieces_complementaires_demandees, admis, refuse, inscrit
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('application_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('document_type');
            $table->string('file_path');
            $table->string('status')->default('en_attente'); // en_attente, valide, rejete
            $table->timestamps();
        });

        Schema::create('application_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('comment')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_status_history');
        Schema::dropIfExists('application_documents');
        Schema::dropIfExists('applications');
    }
};
