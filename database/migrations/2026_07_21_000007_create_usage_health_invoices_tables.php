<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('periodo');
            $table->integer('usuarios_activos')->default(0);
            $table->integer('tickets_creados')->default(0);
            $table->integer('tickets_aprobados')->default(0);
            $table->integer('almacenamiento_mb')->default(0);
            $table->integer('facturas_emitidas')->default(0);
            $table->integer('api_calls')->default(0);
            $table->timestamps();
            $table->unique(['tenant_id', 'periodo']);
        });

        Schema::create('system_health', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tipo', 50);
            $table->string('estado', 20);
            $table->string('valor', 255)->nullable();
            $table->text('mensaje')->nullable();
            $table->timestamps();
            $table->index('tipo');
            $table->index('estado');
        });

        Schema::create('tenant_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('periodo');
            $table->decimal('monto', 10, 2);
            $table->string('moneda', 3)->default('PEN');
            $table->string('estado', 20)->default('pending');
            $table->timestamp('fecha_emision')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->string('metodo_pago', 50)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'periodo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_invoices');
        Schema::dropIfExists('system_health');
        Schema::dropIfExists('tenant_usage');
    }
};