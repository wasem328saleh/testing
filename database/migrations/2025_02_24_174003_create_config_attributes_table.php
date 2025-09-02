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
        Schema::create('config_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name');
            $table->json('rules');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_used_for_filtering')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('config_property_attributes');
    }
};
