<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->text('subject');
            $table->text('purpose');
            $table->date('date_from');
            $table->date('date_to');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_records');
    }
};
