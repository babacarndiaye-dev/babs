<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // CAP, BEP, BT, BTS, Formation qualifiante...
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('grading_systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('scale_max', 6, 2)->default(20);
            $table->string('type')->default('numeric'); // numeric, letter, custom
            $table->decimal('passing_threshold', 6, 2)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_system_id')->constrained()->cascadeOnDelete();
            $table->string('label'); // A, B, C... ou "Très bien"
            $table->decimal('min_score', 6, 2);
            $table->decimal('max_score', 6, 2);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->string('type')->nullable(); // salle, atelier, labo_info, labo_hotellerie...
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('grading_scales');
        Schema::dropIfExists('grading_systems');
        Schema::dropIfExists('levels');
        Schema::dropIfExists('domains');
        Schema::dropIfExists('formation_types');
    }
};
