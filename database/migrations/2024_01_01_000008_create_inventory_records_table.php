<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->string('item_name', 200);
            $table->enum('category', ['วัสดุ', 'ครุภัณฑ์']);
            $table->enum('transaction_type', ['รับ', 'จ่าย']);
            $table->integer('quantity');
            $table->string('unit', 50);
            $table->string('issued_to', 200)->nullable();
            $table->timestamps();

            $table->index('item_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_records');
    }
};
