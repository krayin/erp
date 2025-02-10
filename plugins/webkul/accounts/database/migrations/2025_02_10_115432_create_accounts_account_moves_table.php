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
        Schema::create('accounts_account_moves', function (Blueprint $table) {
            $table->id();

            $table->foreignId('journal_id')->comment()->constrained('accounts_journals');
            $table->foreignId('company_id')->nullable()->comment()->constrained('companies');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts_account_moves');
    }
};
