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
        Schema::create('payments_payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sort')->nullable()->comment('Sort Order');
            $table->foreignId('move_id')->comment('Journal Entry')->constrained('accounts_account_moves')->restrictOnDelete();
            $table->foreignId('journal_id')->nullable()->comment('Journal')->constrained('accounts_journals')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->foreignId('statement_id')->nullable()->comment('Bank Statement')->constrained('accounts_bank_statements')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->comment('Partner')->constrained('partners_partners')->nullOnDelete();
            $table->foreignId('currency_id')->nullable()->comment('Currency')->constrained('currencies')->restrictOnDelete();
            $table->foreignId('foreign_currency_id')->nullable()->comment('Foreign Currency')->constrained('currencies')->restrictOnDelete();
            $table->foreignId('created_id')->nullable()->comment('Created By')->constrained('users')->nullOnDelete();
            $table->string('account_number')->nullable()->comment('Account Number');
            $table->string('partner_name')->nullable()->comment('Partner Name');
            $table->string('transaction_type')->nullable()->comment('Transaction Type');
            $table->string('payment_reference')->nullable()->comment('Payment Reference');
            $table->string('internal_index')->nullable()->comment('Internal Index');
            $table->json('transaction_details')->nullable()->comment('Transaction Details');
            $table->decimal('amount', 20, 2)->comment('Amount');
            $table->decimal('amount_currency', 20, 2)->nullable()->comment('Amount Currency');
            $table->double('amount_residual')->nullable()->comment('Amount Residual');
            $table->boolean('is_reconciled')->default(false)->comment('Is Reconciled');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_payment_transactions');
    }
};
