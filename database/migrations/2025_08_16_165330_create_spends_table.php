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
        Schema::create('spends', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['fornitore', 'ads', 'hosting', 'team', 'influencer', 'altro']);
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('description')->nullable(); // required only for altro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spends');
    }
};
