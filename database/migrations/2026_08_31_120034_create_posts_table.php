<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // User/admin who created the post
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // News or announcement
            $table->enum('type', [
                'news',
                'announcement',
            ]);

            $table->string('title');
            $table->string('slug')->unique();

            // Short description shown on cards
            $table->text('excerpt')->nullable();

            // Full article content
            $table->longText('content');

            // Optional featured image
            $table->string('image')->nullable();

            $table->enum('category', [
                'general',
                'important',
                'community',
                'program',
                'notice',
                'event',
                'other',
            ])->default('general');

            // draft / published / archived
            $table->enum('status', [
                'draft',
                'published',
                'archived',
            ])->default('draft');

            // When the article became visible
            $table->timestamp('published_at')->nullable();

            // Useful for important announcements
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_event') ->default(false);
            $table->date('event_date') ->nullable();
            $table->date('event_end_date') ->nullable();
            $table->time('event_time') ->nullable();
            $table->string('event_location') ->nullable();

            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('published_at');
            $table->index(['is_event', 'event_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};