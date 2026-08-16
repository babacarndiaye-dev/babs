<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // certificat_scolarite, bulletin, recu_paiement...
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('blade_view')->nullable();
            $table->string('numbering_format')->nullable(); // ex: CERT-{year}-{seq}
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_template_id')->constrained()->restrictOnDelete();
            $table->string('reference')->unique();
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            $table->string('hash')->unique();
            $table->string('file_path')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->timestamps();

            $table->index(['documentable_type', 'documentable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_templates');
    }
};
