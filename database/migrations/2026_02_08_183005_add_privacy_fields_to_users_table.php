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
            $table->string('profile_type')->default('public')->after('avatar'); // public или private
            $table->boolean('show_email')->default(false)->after('profile_type');
            $table->boolean('show_memorials')->default(true)->after('show_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_type', 'show_email', 'show_memorials']);
        });
    }
};
