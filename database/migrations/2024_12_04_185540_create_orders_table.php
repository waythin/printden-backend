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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();

            $table->string('order_no');
            $table->double('sub_total')->nullable();
            $table->double('total')->nullable();
            $table->enum('status', ['pending', 'confirm', 'processing', 'failed', 'success', 'delivered'])->default('pending');
            $table->text('location')->nullable(); //address
            $table->enum('delivery_type', ['inside_dhaka', 'outside_dhaka']);
            $table->double('delivery_charge');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
