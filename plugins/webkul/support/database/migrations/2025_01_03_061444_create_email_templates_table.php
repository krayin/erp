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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique()->comment('This code will be used to identify the email template in the code.');
            $table->string('name')->nullable()->comment('This name will be used to identify the email template in the admin panel.');
            $table->string('subject')->nullable()->comment('This subject will be used in the email.');
            $table->text('content')->nullable()->comment('This content will be used in the email.');
            $table->text('description')->nullable()->comment('This description will be used to describe the email template in the admin panel.');
            $table->boolean('is_active')->default(true)->comment('This will determine if the email template is active or not.');
            $table->string('sender_name')->nullable()->comment('This will be used as the sender name in the email.');
            $table->string('sender_email')->nullable()->comment('This will be used as the sender email in the email.');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
