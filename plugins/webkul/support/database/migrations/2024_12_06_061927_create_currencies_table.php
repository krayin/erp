<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->integer('iso_numeric')->nullable();
            $table->tinyInteger('decimal_places')->nullable();
            $table->string('full_name')->nullable();
            $table->decimal('rounding', 8, 2)->default(0.00);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $path = base_path('plugins/webkul/security/app/Data/currencies.json');

        if (File::exists($path)) {
            $currencies = json_decode(File::get($path), true);

            $currencies = collect($currencies)->map(function ($currency) {
                $currency['iso_numeric'] = (int) ($currency['iso_numeric'] ?? null);
                $currency['decimal_places'] = (int) ($currency['decimal_places'] ?? null);
                $currency['rounding'] = (float) ($currency['rounding'] ?? 0.00);
                $currency['active'] = (bool) ($currency['active'] ?? true);
                $currency['created_at'] = now();
                $currency['updated_at'] = now();

                return $currency;
            })->toArray();

            DB::table('currencies')->insert($currencies);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
