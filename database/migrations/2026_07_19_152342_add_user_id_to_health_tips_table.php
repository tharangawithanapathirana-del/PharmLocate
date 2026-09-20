<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            // 🟢 මෙතනට user_id column එක එකතු කරනවා
            $table->unsignedBigInteger('user_id')->nullable();

            // ඔබේ database එකේ users table එකත් තියෙන නිසා foreign key එකක් දාන්න (අනිවාර්ය නැත, හොඳ පුරුද්දක්)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};