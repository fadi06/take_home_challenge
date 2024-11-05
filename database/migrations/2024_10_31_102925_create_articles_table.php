<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('category_id')->constrained('categories')->default('1');
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('country')->nullable()->default('us');
            $table->string('language')->default('en');
            $table->string('author')->nullable();
            $table->text('image')->nullable();
            $table->string('source')->nullable;
            $table->string('url')->nullable();
            $table->string('feed')->default('newsapi');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
