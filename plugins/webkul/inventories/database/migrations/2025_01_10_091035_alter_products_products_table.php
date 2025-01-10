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
        Schema::table('products_products', function (Blueprint $table) {
            $table->integer('sale_delay')->nullable();
            $table->integer('tracking')->nullable();
            $table->text('description_picking')->nullable();
            $table->text('description_pickingout')->nullable();
            $table->text('description_pickingin')->nullable();
            $table->integer('is_storable')->nullable();
            $table->integer('expiration_time')->nullable();
            $table->integer('use_time')->nullable();
            $table->integer('removal_time')->nullable();
            $table->integer('alert_time')->nullable();
            $table->boolean('use_expiration_date')->nullable();

            $table->foreignId('responsible_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_products', function (Blueprint $table) {
            $table->dropForeign(['responsible_id']);
            $table->dropColumn('responsible_id');

            $table->dropColumn([
                'sale_delay',
                'tracking',
                'description_picking',
                'description_pickingout',
                'description_pickingin',
                'is_storable',
                'expiration_time',
                'use_time',
                'removal_time',
                'alert_time',
                'use_expiration_date',
            ]);
        });
    }
};
