<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique();
            $table->string('firstname', 100);
            $table->string('lastname', 100);
            $table->string('grade', 10);
            $table->foreignId('dormitory_id')->constrained()->restrictOnDelete();
            $table->timestamps();

            $table->index(['firstname', 'lastname']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
