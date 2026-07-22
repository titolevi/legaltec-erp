<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // Usar DB raw para evitar problema de SQLite con default values
        // Skip caja_id FK - handled in Model
        DB::statement('ALTER TABLE tickets ADD COLUMN caja_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE tickets ADD COLUMN divisa VARCHAR(3) DEFAULT "PEN"');
        DB::statement('ALTER TABLE tickets ADD COLUMN tipo_transaccion VARCHAR(20) NULL');
        DB::statement('ALTER TABLE tickets ADD COLUMN titular_cuenta VARCHAR(255) NULL');
        DB::statement('ALTER TABLE tickets ADD COLUMN numero_cuenta VARCHAR(100) NULL');
        DB::statement('ALTER TABLE tickets ADD COLUMN banco VARCHAR(100) NULL');
        DB::statement('ALTER TABLE tickets ADD COLUMN campos_extra TEXT NULL');
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