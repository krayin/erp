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
        Schema::create('recruitments_applicants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('source_id')->nullable()->constrained('recruitments_utm_sources')->nullOnDelete();
            $table->foreignId('medium_id')->nullable()->constrained('recruitments_utm_mediums')->nullOnDelete();
            $table->foreignId('candidate_id')->constrained('recruitments_candidates')->restrictOnDelete();
            $table->foreignId('stage_id')->nullable()->constrained('recruitments_stages')->restrictOnDelete();
            $table->foreignId('last_stage_id')->nullable()->constrained('recruitments_stages')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('recruiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('job_id')->nullable()->constrained('employees_job_positions')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('employees_departments')->nullOnDelete();
            $table->foreignId('refuse_reason_id')->nullable()->constrained('recruitments_refuse_reasons')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('email_cc')->nullable()->comment('Email CC');
            $table->string('priority')->nullable()->comment('Evaluation');
            $table->string('salary_proposed_extra')->nullable()->comment('Salary Proposed Extra');
            $table->string('salary_expected_extra')->nullable()->comment('Salary Expected Extra');
            $table->json('applicant_properties')->nullable()->comment('Applicant Properties');
            $table->text('applicant_notes')->nullable()->comment('Applicant Notes');
            $table->boolean('is_active')->default(false)->comment('Active Status');
            $table->date('create_date')->nullable()->comment('Applied On');
            $table->date('date_closed')->nullable()->comment('Hired Date');
            $table->date('date_opened')->nullable()->comment('Assigned');
            $table->date('date_last_stage_updated')->nullable()->comment('Last Stage Updated');
            $table->date('refuse_date')->nullable()->comment('Refused Date');

            $table->double('probability')->nullable()->comment('Probability');
            $table->double('salary_proposed')->nullable()->comment('Salary Proposed');
            $table->double('salary_expected')->nullable()->comment('Salary Expected');
            $table->double('delay_close')->nullable()->comment('Delay Close');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_applicants');
    }
};
