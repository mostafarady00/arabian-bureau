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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

// الحقول القابلة للترجمة (JSON)
            $table->json('inspections'); // يشمل اللغتين
            $table->json('title')->nullable(); // العنوان باللغتين
            $table->json('description'); // الوصف باللغتين
            $table->string('image'); // صورة الفحص
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
