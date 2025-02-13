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
        Schema::create('utm_campaigns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->comment('Responsible user')->constrained('users')->restrictOnDelete();
            $table->foreignId('stage_id')->comment('Stage')->constrained('utm_stages')->restrictOnDelete();
            $table->string('color')->nullable()->comment('Color');
            $table->foreignId('created_by')->nullable()->comment('Created By')->constrained('users')->nullOnDelete();
            $table->string('name')->comment('Campaign Identifier');
            $table->string('title')->comment('Campaign Name');
            $table->boolean('is_active')->comment('Is Active')->default(false);
            $table->boolean('is_auto_campaign')->comment('Is Auto Campaign')->default(false);
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utm_campaigns');
    }
};
