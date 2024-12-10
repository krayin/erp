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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('code', 2)->nullable();
            $table->string('name')->nullable();
            $table->boolean('state_required')->default(false);
            $table->boolean('zip_required')->default(false);

            $table->foreign('currency_id')
                ->references('id')
                ->on('currencies')
                ->onDelete('set null')
                ->onUpdate('no action');

            $table->timestamps();
        });

        $this->insertCountryData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
        });

        Schema::dropIfExists('countries');
    }

    /**
     * Insert country data from JSON file
     */
    private function insertCountryData(): void
    {
        $path = base_path('plugins/webkul/security/app/Data/countries.json');

        if (File::exists($path)) {
            $countries = json_decode(File::get($path), true);

            $formattedCountries = collect($countries)->map(function ($country) {
                return [
                    'currency_id'    => (int) $country['currency_id'] ?? null,
                    'phone_code'     => (int) $country['phone_code'] ?? null,
                    'code'           => $country['code'] ?? null,
                    'name'           => $country['name'] ?? null,
                    'state_required' => (bool) $country['state_required'] === 't',
                    'zip_required'   => (bool) $country['zip_required'] === 't',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->toArray();

            DB::table('countries')->insert($formattedCountries);
        }
    }
};
