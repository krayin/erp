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
        Schema::create('invoices_payment_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->integer('sort')->nullable()->integer()->comment('Sort');
            $table->integer('discount_days')->nullable()->integer()->comment('Discount Days');
            $table->foreignId('creator_id')->nullable()->comment('Creator')->constrained('users')->nullOnDelete();
            $table->string('early_pay_discount')->nullable()->comment('Cash Discount Tax Reduction');
            $table->string('name')->comment('Name');
            $table->string('note')->nullable()->comment('Note');
            $table->boolean('is_active')->default(false)->nullable()->comment('Active');
            $table->boolean('display_on_invoice')->default(false)->nullable()->comment('Display on Invoice');
            $table->boolean('early_discount')->default(false)->nullable()->comment('Early Discount');
            $table->double('discount_percentage')->nullable()->comment('Discount Percentage');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_payment_terms');
    }
};
