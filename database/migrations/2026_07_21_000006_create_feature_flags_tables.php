<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->boolean('activo_global')->default(false);
            $table->timestamps();
        });

        Schema::create('tenant_feature_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('feature_flag_id')->constrained('feature_flags')->cascadeOnDelete();
            $table->boolean('activo')->default(false);
            $table->timestamps();
            $table->unique(['tenant_id', 'feature_flag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_feature_flags');
        Schema::dropIfExists('feature_flags');
    }
};