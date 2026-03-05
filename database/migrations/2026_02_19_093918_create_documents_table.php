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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crew_id')->constrained()->cascadeOnDelete();
            $table->foreignId('document_type_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('code');
            $table->date('issued_date');
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
