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
        Schema::create('invoices_tax_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_tax_groups');
    }
};
