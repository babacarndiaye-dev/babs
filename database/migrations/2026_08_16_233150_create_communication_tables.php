<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->longText('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('brouillon'); // brouillon, publie, archive
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('start_at');
            $table->timestamp('end_at')->nullable();
            $table->string('status')->default('brouillon'); // brouillon, publie, archive
            $table->timestamps();
        });

        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('type')->default('image'); // image, video
            $table->string('file_path')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('gallery_categories');
        Schema::dropIfExists('events');
        Schema::dropIfExists('news');
    }
};
