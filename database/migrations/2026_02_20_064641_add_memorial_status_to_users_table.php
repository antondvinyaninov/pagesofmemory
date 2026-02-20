<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_memorial')->default(false)->after('email_verified_at');
            $table->foreignId('memorial_id')->nullable()->after('is_memorial')->constrained('memorials')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['memorial_id']);
            $table->dropColumn(['is_memorial', 'memorial_id']);
        });
    }
};
