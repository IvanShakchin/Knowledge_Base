<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Изменяем тип колонки status для добавления нового значения
        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published', 'published_auth', 'pending') NOT NULL DEFAULT 'draft'");
    }

    public function down()
    {
        // Возвращаем обратно к старому формату
        DB::statement("ALTER TABLE articles MODIFY COLUMN status ENUM('draft', 'published', 'pending') NOT NULL DEFAULT 'draft'");
    }
};
