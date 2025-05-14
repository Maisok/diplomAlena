<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_category_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            
            $table->index(['group_id', 'date']);
            $table->index(['activity_category_id', 'date']);
            $table->index(['start_time', 'end_time', 'date']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('schedule_items');
    }
};
