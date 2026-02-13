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
            $table->text('achievements')->nullable()->after('character_traits');
            $table->string('military_service')->nullable()->after('achievements');
            $table->string('military_rank')->nullable()->after('military_service');
            $table->string('military_years')->nullable()->after('military_rank');
            $table->text('military_details')->nullable()->after('military_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('memorials', function (Blueprint $table) {
            $table->dropColumn(['achievements', 'military_service', 'military_rank', 'military_years', 'military_details']);
        });
    }
};
