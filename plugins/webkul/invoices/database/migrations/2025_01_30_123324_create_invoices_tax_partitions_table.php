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
        Schema::create('invoices_tax_partitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->nullable()->comment('Account')->constrained('invoices_accounts')->nullOnDelete();
            $table->foreignId('tax_id')->nullable()->comment('Tax')->constrained('invoices_taxes')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->string('repartition_type')->comment('Repartition Type');
            $table->string('document_type')->comment('Document Type');
            $table->string('use_in_tax_closing')->nullable()->comment('Use in Tax Closing');
            $table->double('factor')->nullable()->comment('Factor');
            $table->double('factor_percent')->nullable()->comment('Factor Percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_tax_partitions');
    }
};
