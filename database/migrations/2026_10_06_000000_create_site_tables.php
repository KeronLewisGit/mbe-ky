<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('reference')->nullable()->unique();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('summary')->nullable();
            $table->json('data')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();
            $table->string('status')->default('new')->index();
            $table->text('notes')->nullable();
            $table->boolean('is_sample')->default(false);
            $table->timestamps();
        });

        Schema::create('sailings', function (Blueprint $table) {
            $table->id();
            $table->date('cutoff_date');
            $table->date('sailing_date');
            $table->date('in_hand_date');
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->string('link_text')->nullable();
            $table->string('link_url')->nullable();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('image')->nullable();
            $table->text('body');
            $table->date('published_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('sailings');
        Schema::dropIfExists('enquiries');
    }
};
