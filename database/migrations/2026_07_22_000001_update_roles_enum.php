<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL doesn't support ALTER ENUM directly, need to use raw SQL
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('super_admin','support_admin','support_agent','admin','autorizador','cajero','usuario') NOT NULL DEFAULT 'usuario'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('usuario','autorizador','cajero','admin') NOT NULL DEFAULT 'usuario'");
    }
};