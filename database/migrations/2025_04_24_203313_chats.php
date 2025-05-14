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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20); // 'parent_educator' или 'parent_admin'
            $table->foreignId('parent_id')->constrained('users');
            $table->foreignId('participant_id')->constrained('users');
            $table->timestamps();
            
            $table->unique(['parent_id', 'participant_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
