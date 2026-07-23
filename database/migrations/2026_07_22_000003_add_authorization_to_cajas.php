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

        // Crear tabla de solicitudes (tickets de caja - separada de tickets existentes)
        if (!Schema::hasTable('solicitudes')) {
            Schema::create('solicitudes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('caja_id')->constrained()->cascadeOnDelete();
                $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('concepto');
                $table->decimal('monto', 12, 2);
                $table->string('moneda', 3)->default('PEN');
                $table->string('status', 20)->default('pendiente');
                $table->foreignId('autorizador_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('autorizado_at')->nullable();
                $table->text('motivo_rechazo')->nullable();
                $table->foreignId('cajero_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('atendido_at')->nullable();
                $table->text('notas')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
        Schema::table('cajas', function (Blueprint $table) {
            $table->dropColumn('require_authorization');
        });
    }
};