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
        Schema::table('recruitments_applicants', function (Blueprint $table) {
            $table->string('state')->nullable()->after('refuse_reason_id')->default('normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recruitments_applicants', function (Blueprint $table) {
            $table->dropColumn('state');
        });
    }
};
