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
        Schema::create('invoices_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->nullable()->comment('Currency')->constrained('currencies')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->comment('Creator')->constrained('users')->nullOnDelete();
            $table->string('account_type')->comment('Account Type');
            $table->string('name')->comment('Name');
            $table->string('code')->nullable()->comment('Code');
            $table->string('note')->nullable()->comment('Note');
            $table->boolean('deprecated')->nullable()->comment('Deprecated');
            $table->boolean('reconcile')->nullable()->comment('Reconcile');
            $table->boolean('non_trade')->nullable()->comment('Non Trade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_accounts');
    }
};
