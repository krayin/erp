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
        Schema::create('recruitments_candidates', function (Blueprint $table) {
            $table->id();

            $table->integer('message_bounced')->nullable()->default(0)->comment('Message Bounced');
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('partner_id')->nullable()->constrained('partners_partners')->nullOnDelete();
            $table->foreignId('degree_id')->nullable()->constrained('recruitments_degrees')->nullOnDelete();
            $table->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees_employees')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('email_cc')->nullable()->comment('Email CC');
            $table->string('name')->nullable()->comment('Partner Name');
            $table->string('email_from')->nullable()->comment('Email From');
            $table->string('phone')->nullable()->comment('Partner Phone');
            $table->string('linkedin_profile')->nullable()->comment('Linkedin Profile');
            $table->integer('priority')->nullable()->default(0)->comment('Priority');
            $table->date('availability_date')->nullable()->comment('Availability Date');
            $table->json('candidate_properties')->nullable()->comment('Candidate Properties');
            $table->boolean('is_active')->nullable()->default(1)->comment('Is Active');
            $table->string('color')->nullable()->comment('Color');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recruitments_candidates');
    }
};
