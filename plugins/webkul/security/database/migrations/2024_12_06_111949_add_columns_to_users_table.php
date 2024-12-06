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
        Schema::table('users', function (Blueprint $table) {
            $table->string('language')->nullable()->after('password');
            $table->boolean('is_active')->default(true)->after('language');
            $table->unsignedBigInteger('default_company_id')->nullable()->after('email');
            $table->foreign('default_company_id')->references('id')->on('companies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['default_company_id']);
            $table->dropColumn('default_company_id');
        });
    }
};
