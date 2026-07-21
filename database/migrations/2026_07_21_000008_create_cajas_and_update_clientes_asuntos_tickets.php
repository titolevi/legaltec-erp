<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Actualizar clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('contacto', 255)->nullable()->after('nombre');
            $table->string('ruc', 11)->nullable()->after('contacto');
            $table->text('direccion_fiscal')->nullable()->after('ruc');
            $table->string('po_box', 50)->nullable()->after('direccion_fiscal');
            $table->string('socio_responsable', 255)->nullable()->after('po_box');
            $table->string('abogado_asignado', 255)->nullable()->after('socio_responsable');
        });

        // Actualizar asuntos
        Schema::table('asuntos', function (Blueprint $table) {
            $table->string('abogado_responsable', 255)->nullable()->after('nombre');
            $table->integer('id_time_manager')->nullable()->after('abogado_responsable');
        });

        // Crear tabla cajas
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->string('slug', 50);
            $table->text('descripcion')->nullable();
            $table->string('tipo', 50)->default('general'); // general, movilidad
            $table->string('moneda', 3)->default('PEN');
            $table->decimal('monto_maximo', 10, 2)->nullable();
            $table->string('color', 7)->default('#6366f1');
            $table->string('icono', 50)->default('💰');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['tenant_id', 'slug']);
            $table->index('tenant_id');
        });

        // Crear tabla caja_autorizadores
        Schema::create('caja_autorizadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('limite_aprobacion', 10, 2)->nullable();
            $table->timestamps();
            $table->unique(['caja_id', 'user_id']);
        });

        // Crear tabla caja_cajeros
        Schema::create('caja_cajeros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['caja_id', 'user_id']);
        });

        // Actualizar tickets con nuevos campos
        Schema::table('tickets', function (Blueprint $table) {
            // Renombrar y reorganizar
            $table->foreignId('caja_id')->nullable()->after('tenant_id')->constrained()->nullOnDelete();
            $table->index('caja_id');

            // Nuevos campos comunes
            $table->string('divisa', 3)->default('PEN')->after('monto');
            $table->string('tipo_transaccion', 20)->nullable()->after('divisa'); // efectivo, transferencia
            $table->string('titular_cuenta', 255)->nullable()->after('tipo_transaccion');
            $table->string('numero_cuenta', 100)->nullable()->after('titular_cuenta');
            $table->string('banco', 100)->nullable()->after('numero_cuenta');

            // Campos extra para movilidad (JSON)
            $table->json('campos_extra')->nullable()->after('observaciones');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['caja_id', 'divisa', 'tipo_transaccion', 'titular_cuenta', 'numero_cuenta', 'banco', 'campos_extra']);
        });
        Schema::dropIfExists('caja_cajeros');
        Schema::dropIfExists('caja_autorizadores');
        Schema::dropIfExists('cajas');
        Schema::table('asuntos', function (Blueprint $table) {
            $table->dropColumn(['abogado_responsable', 'id_time_manager']);
        });
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['contacto', 'ruc', 'direccion_fiscal', 'po_box', 'socio_responsable', 'abogado_asignado']);
        });
    }
};