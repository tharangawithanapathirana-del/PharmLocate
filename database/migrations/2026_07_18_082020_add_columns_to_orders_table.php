<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('pharmacy_id')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->text('delivery_address')->nullable();
            $table->enum('delivery_type', ['delivery', 'pickup'])->default('delivery');
            $table->enum('status', ['pending', 'confirmed', 'processing', 'dispatched', 'delivered', 'cancelled'])->default('pending');
            $table->string('prescription_image')->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'pharmacy_id', 'total_amount', 'delivery_address', 'delivery_type', 'status', 'prescription_image', 'notes']);
        });
    }
};