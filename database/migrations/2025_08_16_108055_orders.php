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
            $table->string('product_name');
            $table->string('size');
            $table->string('shopify_order_id')->unique();
            $table->string('tracking_number')->nullable()->unique();
            $table->string('product_image')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_address');
            $table->decimal('price', 10, 2);
            $table->enum('status', [
                'shipped',
                'delivered',
                'delayed',
                'canceled',
            ])->default('shipped');
            $table->text('notes')->nullable();
            $table->foreignId('confirmation_price_id')->nullable()->constrained('confirmation_prices');
            $table->decimal('confirmed_price', 10, 2)->nullable(); // cached copy
            $table->foreignId('operator_id')->nullable()->constrained('users');
            $table->foreignId('fornissure_id')->nullable()->constrained('users');
            $table->timestamp('notified_at')->nullable(); // for tracking notifications
            $table->timestamps();

            $table->index('status');
            $table->index('fornissure_id');
            $table->index('operator_id');
            $table->index('confirmation_price_id');
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
