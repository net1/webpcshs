<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->string('location', 200);
            $table->string('category', 100);
            $table->text('problem');
            $table->enum('urgency', ['ด่วนมาก', 'ด่วน', 'ปกติ']);
            $table->enum('repair_status', ['รอดำเนินการ', 'กำลังดำเนินการ', 'เสร็จสิ้น'])
                  ->default('รอดำเนินการ');
            $table->timestamps();

            $table->index('repair_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_records');
    }
};
