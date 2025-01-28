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
        Schema::create('sales_teams', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->default(0)->nullable();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete()->comment('Company ID');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Team Leader');
            $table->string('color')->nullable()->comment('Color');
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete()->comment('Team Leader');
            $table->string('name')->comment('Name');
            $table->boolean('is_active')->nullable()->default(0)->comment('Is Active');
            $table->double('invoiced_target')->nullable()->comment('Invoiced Target');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_teams');
    }
};
