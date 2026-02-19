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
        Schema::table('memorials', function (Blueprint $table) {
            $table->decimal('burial_latitude', 13, 10)->nullable()->change();
            $table->decimal('burial_longitude', 13, 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->decimal('burial_latitude', 10, 8)->nullable()->change();
            $table->decimal('burial_longitude', 11, 8)->nullable()->change();
        });
    }
};
