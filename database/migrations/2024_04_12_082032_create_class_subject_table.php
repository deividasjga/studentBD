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
        Schema::create('class_subject', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();
            $table->foreign('class_id')
                ->references('id')
                ->on('class')
                ->onDelete('cascade');
            $table->foreign('subject_id')
                ->references('id')
                ->on('subject')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject');
    }
};
