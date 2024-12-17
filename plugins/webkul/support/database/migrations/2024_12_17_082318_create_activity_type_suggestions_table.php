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
        Schema::create('activity_type_suggestions', function (Blueprint $table) {
            $table->unsignedBigInteger('activity_type_id')->comment('The primary activity type');
            $table->unsignedBigInteger('suggested_activity_type_id')->comment('The suggested activity type');

            $table->foreign('activity_type_id', 'activity_type_id')
                ->references('id')
                ->on('activity_types')
                ->onDelete('cascade');

            $table->foreign('suggested_activity_type_id', 'suggested_activity_type_id')
                ->references('id')
                ->on('activity_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_type_suggestions', function (Blueprint $table) {
            $table->dropForeign('activity_type_id');
            $table->dropForeign('suggested_activity_type_id');
        });

        Schema::dropIfExists('activity_type_suggestions');
    }
};
