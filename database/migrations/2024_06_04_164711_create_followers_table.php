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
        Schema::create('followers', function (Blueprint $table) {
            $table->unsignedBigInteger('user');
            $table->unsignedBigInteger('follower');
            $table->timestamps();

            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('follower')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user', 'follower']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
