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
        Schema::table('users', function (Blueprint $table) {
            // إزالة القيد الفريد الحالي
            $table->dropUnique(['email']);

            // إضافة قيد فريد جزئي (يسمح بتكرار الإيميل إذا كان verify = false)
            $table->unique(['email', 'verify']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email', 'verify']);
            $table->string('email')->unique()->change();
        });
    }
};
