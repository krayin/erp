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
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('partner_address_id')->comment('Partner Address')->after('id');
            $table->foreign('partner_address_id')->references('id')->on('partners_addresses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('company_addresses', function (Blueprint $table) {
            $table->dropForeign(['partner_address_id']);

            $table->dropColumn('partner_address_id');
        });
    }
};
