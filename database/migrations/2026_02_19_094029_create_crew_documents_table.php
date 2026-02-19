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
        Schema::create('crew_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('crew_id');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('user_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('code');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->timestamps();

            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_documents');
    }
};
