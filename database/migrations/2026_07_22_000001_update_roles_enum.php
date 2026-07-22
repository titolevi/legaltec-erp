<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: altera el ENUM para incluir nuevos roles
        // SQLite: no soporta ENUM ni MODIFY, usa TEXT por defecto (ya funciona)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('super_admin','support_admin','support_agent','admin','autorizador','cajero','usuario') NOT NULL DEFAULT 'usuario'");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('usuario','autorizador','cajero','admin') NOT NULL DEFAULT 'usuario'");
        }
    }
};