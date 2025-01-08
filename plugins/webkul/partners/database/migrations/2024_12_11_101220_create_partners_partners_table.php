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
        Schema::create('partners_partners', function (Blueprint $table) {
            $table->id();
            $table->string('account_type')->default('individual');
            $table->string('sub_type')->nullable()->index()->default('partner');
            $table->string('name')->index();
            $table->string('avatar')->nullable();
            $table->string('email')->nullable();
            $table->string('job_title')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('mobile')->nullable()->index();
            $table->string('color')->nullable();
            $table->string('company_registry')->nullable()->index();
            $table->string('reference')->nullable()->index();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('title_id')
                ->nullable()
                ->constrained('partners_titles')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('industry_id')
                ->nullable()
                ->constrained('partners_industries')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners_partners');
    }
};
