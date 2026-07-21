<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('asunto_id')->constrained('asuntos');
            $table->string('codigo_asunto', 20);
            $table->date('fecha_diligencia');
            $table->text('detalle');
            $table->string('distrito', 100)->nullable();
            $table->boolean('facturable')->default(true);
            $table->decimal('monto', 10, 2)->default(0);
            $table->string('moneda', 3)->default('PEN');
            $table->string('ejecutado_por', 255);
            $table->foreignId('autorizador_id')->nullable()->constrained('users');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'completado'])->default('pendiente');
            $table->foreignId('usuario_id')->constrained('users');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};