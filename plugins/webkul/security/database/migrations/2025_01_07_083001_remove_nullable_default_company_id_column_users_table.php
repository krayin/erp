<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_company_id']);
        });

        DB::table('users')->whereNull('default_company_id')->update(['default_company_id' => 1]);

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('default_company_id')->nullable(false)->change();

            $table->foreign('default_company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_company_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('default_company_id')->nullable()->change();

            $table->foreign('default_company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }
};
