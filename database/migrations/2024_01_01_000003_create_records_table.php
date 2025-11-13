<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->enum('record_type', [
                'dormitory',
                'food-stock',
                'food-distribute',
                'hospital',
                'uniform',
                'duty',
                'repair',
                'inventory-receive',
                'inventory-issue',
                'permission',
                'student-leave'
            ]);
            $table->date('record_date');
            $table->foreignId('dormitory_id')->nullable()->constrained()->nullOnDelete();
            $table->text('details')->nullable();
            $table->string('recorder', 100);
            $table->enum('report_status', ['รอรับรองรายงาน', 'รับรองรายงาน', 'ไม่รับรองรายงาน'])
                  ->default('รอรับรองรายงาน');
            $table->string('approval_name', 100)->nullable();
            $table->text('approval_details')->nullable();
            $table->date('approval_date')->nullable();
            $table->timestamp('approval_timestamp')->nullable();
            $table->timestamps();

            $table->index('record_type');
            $table->index('record_date');
            $table->index('report_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
