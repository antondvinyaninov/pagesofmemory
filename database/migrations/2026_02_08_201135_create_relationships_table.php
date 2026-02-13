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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memorial_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('relationship_type'); // spouse, parent, child, sibling, friend, colleague, etc.
            $table->string('custom_relationship')->nullable(); // если выбрано "другое"
            $table->boolean('confirmed')->default(false); // подтверждена ли связь
            $table->boolean('visible')->default(true); // видна ли публично
            $table->text('notes')->nullable(); // заметки
            $table->timestamps();
            
            // Уникальность: один пользователь не может иметь две одинаковые связи с одним мемориалом
            $table->unique(['memorial_id', 'user_id', 'relationship_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
