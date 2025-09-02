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
        Schema::create('merchant_register_orders', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->date('date');
            $table->enum('status', ['pending', 'accept','unacceptable'])->default('pending');
            $table->text('reply')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_register_orders');
    }
};
