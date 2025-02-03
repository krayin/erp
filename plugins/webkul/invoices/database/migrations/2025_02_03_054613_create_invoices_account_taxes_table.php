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
        Schema::create('invoices_account_taxes', function (Blueprint $table) {
            $table->foreignId('account_id')->constrained('invoices_accounts')->cascadeOnDelete();
            $table->foreignId('tax_id')->constrained('invoices_taxes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_account_taxes');
    }
};
