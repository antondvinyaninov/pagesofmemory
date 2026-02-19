<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->string('privacy')->default('public')->after('status');
            $table->boolean('moderate_memories')->default(false)->after('privacy');
            $table->boolean('allow_comments')->default(true)->after('moderate_memories');
            $table->json('military_files')->nullable()->after('military_details');
            $table->json('achievement_files')->nullable()->after('military_files');

            $table->index('privacy');
        });
    }

    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropIndex(['privacy']);
            $table->dropColumn([
                'privacy',
                'moderate_memories',
                'allow_comments',
                'military_files',
                'achievement_files',
            ]);
        });
    }
};
