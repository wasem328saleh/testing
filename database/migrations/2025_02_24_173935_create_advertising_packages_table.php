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
        Schema::create('advertising_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('price_history');
            $table->integer('validity_period'); //Days
            $table->integer('number_of_advertisements');
            $table->integer('validity_period_per_advertisement'); //Days
            $table->text('description')->default('');
            $table->enum('user_type', ['merchant','user','both'])->default('both');
//            $table->text('image_url')->default('');
//            $table->boolean('has_coupon')->default(false);
//            $table->boolean('has_discount')->default(false);
//            $table->double('discount_value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_packages');
    }
};
