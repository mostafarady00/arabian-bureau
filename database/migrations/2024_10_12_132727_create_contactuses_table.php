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
        Schema::create('contactuses', function (Blueprint $table) {
            $table->id();
            $table->json('address'); // الحقل قابل للترجمة
            $table->string('tel');
            $table->string('email');
            $table->string('lat'); // خط العرض
            $table->string('lng'); // خط الطول
            $table->enum('type', ['enquire', 'cert', 'other'])->default('enquire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contactuses');
    }
};
