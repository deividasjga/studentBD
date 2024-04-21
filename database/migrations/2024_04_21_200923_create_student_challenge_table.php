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
        Schema::create('student_challenge', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('challenge_id');
            $table->boolean('completed')->default(false);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('challenge_id')->references('id')->on('challenge')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_challenge');
    }
};
