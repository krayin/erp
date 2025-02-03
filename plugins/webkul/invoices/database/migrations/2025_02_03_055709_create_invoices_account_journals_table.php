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
        Schema::create('invoices_account_journals', function (Blueprint $table) {
            $table->foreignId('account_id')->constrained('invoices_accounts')->cascadeOnDelete();
            $table->foreignId('journal_id')->constrained('invoices_journals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_account_journals');
    }
};
