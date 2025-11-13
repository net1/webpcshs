<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('record_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_name', 200)->nullable();
            $table->string('status', 50)->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index('record_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('record_students');
    }
};
