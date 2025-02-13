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
        Schema::create('payments_payment_tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payments_payment_methods')->restrictOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners_partners')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('payment_details')->nullable();
            $table->string('provider_reference_id')->nullable();
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_payment_tokens');
    }
};
