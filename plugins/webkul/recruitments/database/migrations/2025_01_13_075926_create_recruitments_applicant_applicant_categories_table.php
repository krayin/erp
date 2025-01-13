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
        Schema::create('recruitments_applicant_applicant_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('applicant_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->foreign('applicant_id')->references('id')->on('recruitments_applicants')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('recruitments_applicant_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_applicant_applicant_categories');
    }
};
