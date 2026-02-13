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
        Schema::create('memorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Основная информация
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('birth_date');
            $table->date('death_date');
            $table->string('birth_place')->nullable();
            $table->string('photo')->nullable();
            
            // Биография
            $table->text('biography')->nullable();
            $table->text('full_biography')->nullable();
            $table->text('necrologue')->nullable();
            $table->string('education')->nullable();
            $table->string('education_details')->nullable();
            $table->string('career')->nullable();
            $table->string('career_details')->nullable();
            $table->text('hobbies')->nullable();
            $table->text('character_traits')->nullable();
            
            // Захоронение
            $table->string('burial_place')->nullable();
            $table->string('burial_address')->nullable();
            $table->string('burial_location')->nullable();
            $table->decimal('burial_latitude', 10, 8)->nullable();
            $table->decimal('burial_longitude', 11, 8)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memorials');
    }
};
