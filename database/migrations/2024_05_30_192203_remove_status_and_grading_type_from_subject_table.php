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
        Schema::table('subject', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('grading_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1);
            $table->string('grading_type')->nullable();
        });
    }
};
