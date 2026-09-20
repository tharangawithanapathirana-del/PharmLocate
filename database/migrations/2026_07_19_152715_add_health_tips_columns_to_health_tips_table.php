<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            // 🟢 මෙතනට අදාල තීරු තුන එකතු කරනවා
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('category')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('health_tips', function (Blueprint $table) {
            $table->dropColumn(['title', 'content', 'category']);
        });
    }
};