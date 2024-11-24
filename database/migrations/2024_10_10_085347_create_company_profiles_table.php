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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();

            $table->string('main_image');
            $table->string('icon');
            $table->string('organization_image');

            // الحقول القابلة للترجمة ستكون نصية (JSON)
            $table->json('company_profile'); // يشمل اللغتين
            $table->json('business_interest'); // يشمل اللغتين
            $table->json('organization_subdesc'); // يشمل اللغتين
            $table->json('organization_desc'); // يشمل اللغتين

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
