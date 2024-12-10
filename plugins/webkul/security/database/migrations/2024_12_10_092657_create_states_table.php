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
        Schema::create('states', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('country_id');
            $table->string('name');
            $table->string('code');

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->timestamps();
        });

        $this->insertStateData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('states', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
        });

        Schema::dropIfExists('states');
    }

    /**
     * Insert state data from JSON file
     */
    private function insertStateData(): void
    {
        $path = base_path('plugins/webkul/security/app/Data/states.json');

        if (File::exists($path)) {
            $states = json_decode(File::get($path), true);

            $formattedStates = collect($states)->map(function ($state) {
                return [
                    'country_id' => (int) $state['country_id'] ?? null,
                    'name'       => (string) $state['name'] ?? null,
                    'code'       => (string) $state['code'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('states')->insert($formattedStates);
        }
    }
};
