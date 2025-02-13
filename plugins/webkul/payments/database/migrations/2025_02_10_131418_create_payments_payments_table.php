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
        Schema::create('payments_payment_methods', function (Blueprint $table) {
            $table->id();

            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->foreignId('primary_payment_method_id')->nullable()->comment('Primary Payment Method')->constrained('payments_payment_methods')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->comment('Created By')->constrained('users')->nullOnDelete();
            $table->string('code')->comment('Code');
            $table->string('support_refund')->nullable()->comment('Support Refund');
            $table->string('name')->comment('Name');
            $table->boolean('is_active')->default(1)->comment('Is Active');
            $table->boolean('support_tokenization')->nullable()->comment('Support Tokenization');
            $table->boolean('support_express_checkout')->nullable()->comment('Support Express Checkout');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_payment_method');
    }
};
