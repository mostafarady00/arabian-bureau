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
        Schema::create('about', function (Blueprint $table) {
            $table->id();

            $table->string('image');
            $table->string('icon');

            // الحقول القابلة للترجمة ستكون نصية (JSON)
            $table->json('about'); // يشمل اللغتين
            $table->json('icon_subdesc'); // يشمل اللغتين
            $table->json('icon_desc'); // يشمل اللغتين


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about');
    }
};
