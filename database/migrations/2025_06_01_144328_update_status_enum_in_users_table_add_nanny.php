<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Обновляем ENUM, добавляя 'nanny'
        DB::statement("ALTER TABLE users MODIFY status ENUM('parent', 'admin', 'educator', 'nanny') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно без 'nanny'
        DB::statement("ALTER TABLE users MODIFY status ENUM('parent', 'admin', 'educator') NOT NULL");
    }
};