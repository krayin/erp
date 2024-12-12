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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->integer('color')->nullable();

            $table->unsignedInteger('company_id')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->unsignedInteger('job_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('work_contact_id')->nullable();
            $table->unsignedInteger('work_location_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('coach_id')->nullable();

            $table->unsignedInteger('private_state_id')->nullable();
            $table->unsignedInteger('private_country_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('country_of_birth')->nullable();
            $table->unsignedInteger('bank_account_id')->nullable();

            $table->integer('distance_home_work')->nullable();
            $table->integer('km_home_work')->nullable();
            $table->unsignedInteger('departure_reason_id')->nullable();

            $table->unsignedInteger('create_uid')->nullable();
            $table->unsignedInteger('write_uid')->nullable();

            $table->string('name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('work_email')->nullable();

            $table->string('private_street')->nullable();
            $table->string('private_street2')->nullable();
            $table->string('private_city')->nullable();
            $table->string('private_zip')->nullable();
            $table->string('private_phone')->nullable();
            $table->string('private_email')->nullable();

            $table->string('lang')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital')->nullable();
            $table->string('spouse_complete_name')->nullable();
            $table->string('place_of_birth')->nullable();

            $table->string('ssnid')->nullable();
            $table->string('sinid')->nullable();
            $table->string('identification_id')->nullable();
            $table->string('passport_id')->nullable();
            $table->string('permit_no')->nullable();
            $table->string('visa_no')->nullable();

            $table->string('certificate')->nullable();
            $table->string('study_field')->nullable();
            $table->string('study_school')->nullable();

            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();

            $table->string('distance_home_work_unit')->nullable();
            $table->string('employee_type')->nullable();
            $table->string('barcode')->nullable();
            $table->string('pin')->nullable();
            $table->string('private_car_plate')->nullable();

            $table->date('spouse_birthdate')->nullable();
            $table->date('birthday')->nullable();
            $table->date('visa_expire')->nullable();
            $table->date('work_permit_expiration_date')->nullable();
            $table->date('departure_date')->nullable();

            $table->boolean('active')->nullable();
            $table->boolean('is_flexible')->nullable();
            $table->boolean('is_fully_flexible')->nullable();
            $table->boolean('work_permit_scheduled_activity')->nullable();

            $table->json('employee_properties')->nullable();
            $table->text('additional_note')->nullable();
            $table->text('notes')->nullable();
            $table->text('departure_description')->nullable();

            $table->timestamp('create_date')->nullable();
            $table->timestamp('write_date')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('job_id')->references('id')->on('job_positions')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
