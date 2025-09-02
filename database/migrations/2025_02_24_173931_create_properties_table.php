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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->integer('area')->nullable();
            $table->json('price_history');
            $table->enum('publication_type',['sale','rent']);
            $table->double('sale_price')->nullable();
            $table->json('rent_price')->nullable();
            $table->integer('age')->nullable();
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->string('secondary_address')->nullable();
            $table->json('map_points')->nullable();
//            $table->integer('levels_count')->nullable();
//            $table->string('level_location')->nullable(); // اذا كانت شقة فهاد يعني هيي بأنو طابق
            $table->timestamps();
//            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
