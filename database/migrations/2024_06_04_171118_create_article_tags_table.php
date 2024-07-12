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
        Schema::create('article_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article');
            $table->unsignedBigInteger('tag');

            $table->timestamps();

            $table->foreign('article')->references('id')->on('articles')->onDelete('cascade');
            $table->foreign('tag')->references('id')->on('tags')->onDelete('cascade');

            $table->index(['article', 'tag']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_tags');
    }
};
