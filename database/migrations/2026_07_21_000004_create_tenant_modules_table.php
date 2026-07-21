<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('module_slug', 50);
            $table->string('module_name', 100);
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_activacion')->nullable();
            $table->decimal('precio_mensual', 10, 2)->default(0);
            $table->json('config')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'module_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_modules');
    }
};