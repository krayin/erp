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

            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->unsignedBigInteger('calendar_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('job_id')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();
            $table->unsignedBigInteger('work_location_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('coach_id')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('private_country_id')->nullable();
            $table->unsignedBigInteger('private_state_id')->nullable();
            $table->unsignedBigInteger('country_of_birth')->nullable();
            $table->unsignedBigInteger('bank_account_id')->nullable();
            $table->unsignedBigInteger('departure_reason_id')->nullable();

            $table->string('name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('color')->nullable();
            $table->integer('children')->nullable();
            $table->integer('distance_home_work');
            $table->integer('km_home_work');
            $table->string('distance_home_work_unit');
            $table->string('work_email')->nullable();
            $table->string('private_street1')->nullable();
            $table->string('private_street2')->nullable();
            $table->string('private_city')->nullable();
            $table->string('private_zip')->nullable();
            $table->string('private_phone')->nullable();
            $table->string('private_email')->nullable();
            $table->string('lang')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthday')->nullable();
            $table->string('marital')->nullable();
            $table->string('spouse_complete_name')->nullable();
            $table->string('spouse_birthdate')->nullable();
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
            $table->string('employee_type');
            $table->string('barcode')->nullable();
            $table->string('pin')->nullable();
            $table->string('private_car_plate')->nullable();
            $table->string('visa_expire')->nullable();
            $table->string('work_permit_expiration_date')->nullable();
            $table->string('departure_date')->nullable();
            $table->text('departure_description')->nullable();
            $table->json('employee_properties')->nullable();
            $table->text('additional_note')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->nullable();
            $table->boolean('is_flexible')->nullable();
            $table->boolean('is_fully_flexible')->nullable();
            $table->boolean('work_permit_scheduled_activity')->nullable();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('calendar_id')->references('id')->on('calendars')->onDelete('set null');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('job_id')->references('id')->on('employee_job_positions')->onDelete('set null');
            $table->foreign('partner_id')->references('id')->on('partners_partners')->onDelete('set null');
            $table->foreign('work_location_id')->references('id')->on('work_locations')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('coach_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('private_state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            $table->foreign('private_country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('country_of_birth')->references('id')->on('countries')->onDelete('set null');
            $table->foreign('bank_account_id')->references('id')->on('banks')->onDelete('set null');
            $table->foreign('departure_reason_id')->references('id')->on('departure_reasons')->onDelete('set null');

            $table->softDeletes();
            $table->timestamps();
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
