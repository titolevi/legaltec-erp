<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite no soporta ADD COLUMN con softDeletes fácilmente
            // Recreamos las tablas con la columna deleted_at
            DB::statement('ALTER TABLE clientes ADD COLUMN deleted_at DATETIME NULL');
            DB::statement('ALTER TABLE asuntos ADD COLUMN deleted_at DATETIME NULL');
        } else {
            Schema::table('clientes', fn($t) => $t->softDeletes());
            Schema::table('asuntos', fn($t) => $t->softDeletes());
        }
    }

    public function down(): void
    {
        // No revertir para no perder datos
    }
};