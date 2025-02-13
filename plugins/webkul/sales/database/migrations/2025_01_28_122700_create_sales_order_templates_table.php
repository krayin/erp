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
        Schema::create('sales_order_templates', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable()->default(0);
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->integer('number_of_days')->nullable()->comment('Quotation Duration');
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->comment('Quotation Template');
            $table->text('note')->nullable()->comment('Terms & Conditions');
            $table->integer('journal_id')->nullable()->comment('Invoicing Journal');
            $table->boolean('is_active')->nullable()->default(false)->comment('Status');
            $table->boolean('require_signature')->nullable()->default(false)->comment('Require Signature');
            $table->boolean('require_payment')->nullable()->default(false)->comment('Require Payment');
            $table->double('prepayment_percentage')->nullable()->comment('Prepayment Percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_order_templates');
    }
};
