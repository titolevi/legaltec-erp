<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar require_authorization a cajas
        Schema::table('cajas', function (Blueprint $table) {
            if (!Schema::hasColumn('cajas', 'require_authorization')) {
                $table->boolean('require_authorization')->default(false)->after('monto_maximo');
            }
        });

        // Agregar campos de autorizacion a tickets existentes
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->string('status', 20)->default('pendiente')->after('monto');
            }
            if (!Schema::hasColumn('tickets', 'moneda')) {
                $table->string('moneda', 3)->default('PEN')->after('monto');
            }
            if (!Schema::hasColumn('tickets', 'autorizador_id')) {
                $table->foreignId('autorizador_id')->nullable()->constrained('users')->nullOnDelete()->after('status');
            }
            if (!Schema::hasColumn('tickets', 'autorizado_at')) {
                $table->timestamp('autorizado_at')->nullable()->after('autorizador_id');
            }
            if (!Schema::hasColumn('tickets', 'motivo_rechazo')) {
                $table->text('motivo_rechazo')->nullable()->after('autorizado_at');
            }
            if (!Schema::hasColumn('tickets', 'cajero_id')) {
                $table->foreignId('cajero_id')->nullable()->constrained('users')->nullOnDelete()->after('motivo_rechazo');
            }
            if (!Schema::hasColumn('tickets', 'atendido_at')) {
                $table->timestamp('atendido_at')->nullable()->after('cajero_id');
            }
            if (!Schema::hasColumn('tickets', 'notas')) {
                $table->text('notas')->nullable()->after('atendido_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropColumn('require_authorization');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $columns = ['status', 'moneda', 'autorizador_id', 'autorizado_at', 'motivo_rechazo', 'cajero_id', 'atendido_at', 'notas'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('tickets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};