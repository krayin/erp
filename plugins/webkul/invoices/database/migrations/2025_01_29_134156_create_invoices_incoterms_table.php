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
        Schema::create('invoices_incoterms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->nullable()->comment('Creator')->constrained('users')->nullOnDelete();
            $table->string('code')->comment('Code');
            $table->string('name')->comment('Name');
            $table->boolean('is_active')->default(false)->comment('Status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_incoterms');
    }
};
