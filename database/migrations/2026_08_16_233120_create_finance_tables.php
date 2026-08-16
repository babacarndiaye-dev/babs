<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Inscription, Scolarite, Examen, Stage...
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('fee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->foreignId('fee_type_id')->constrained()->cascadeOnDelete();
            $table->string('frequency')->default('unique'); // unique, mensuel, trimestriel, semestriel, personnalise
            $table->decimal('amount', 12, 2);
            $table->unsignedInteger('installments_count')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // especes, wave, orange_money, free_money, virement, carte
            $table->boolean('is_active')->default(true);
            $table->boolean('is_online')->default(false);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $table->string('invoice_number')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->string('status')->default('en_attente'); // en_attente, partiel, paye, en_retard, annule
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('reference')->nullable();
            $table->string('status')->default('valide'); // en_attente, valide, echoue, rembourse
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->string('pdf_path')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoice_lines');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('fee_schedules');
        Schema::dropIfExists('fee_types');
    }
};
