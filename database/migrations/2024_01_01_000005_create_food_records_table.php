<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained()->cascadeOnDelete();
            $table->enum('food_type', ['นม', 'ขนมปัง']);
            $table->enum('transaction_type', ['รับ', 'จ่าย']);
            $table->integer('quantity');
            $table->timestamps();

            $table->index('food_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_records');
    }
};
