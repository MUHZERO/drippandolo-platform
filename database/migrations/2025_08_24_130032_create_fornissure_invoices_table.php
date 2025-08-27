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
        Schema::create('fornissure_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornissure_id')->constrained('users')->cascadeOnDelete();
            $table->string('reference')->unique();
            $table->enum('type', ['payment', 'return']); // payment = every 3 days, return = every 20 days
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('status', ['not_paid', 'paid'])->default('not_paid');
            $table->string('transaction_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fornissure_invoices');
    }
};
