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
        Schema::create('chatter_attachments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->nullable()->comment('Company');
            $table->unsignedBigInteger('creator_id')->nullable()->comment('Created By');
            $table->unsignedBigInteger('message_id')->nullable()->comment('Message');
            $table->string('file_size')->nullable()->comment('File Size');
            $table->string('name')->nullable()->comment('Name');
            $table->morphs('messageable');
            $table->string('file_path')->nullable()->comment('File Path');
            $table->string('original_file_name')->nullable()->comment('Original File Name');
            $table->string('mime_type')->nullable()->comment('Mime Type');

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('chatter_messages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatter_attachments');
    }
};
