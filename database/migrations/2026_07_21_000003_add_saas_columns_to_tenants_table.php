<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('slug');
            $table->string('plan', 50)->default('trial')->after('status');
            $table->decimal('mrr', 10, 2)->default(0)->after('plan');
            $table->bigInteger('storage_limit')->default(1024)->after('mrr');
            $table->bigInteger('storage_used')->default(0)->after('storage_limit');
            $table->integer('max_users')->default(10)->after('storage_used');
            $table->boolean('maintenance_mode')->default(false)->after('max_users');
            $table->text('maintenance_message')->nullable()->after('maintenance_mode');
            $table->text('notas')->nullable()->after('maintenance_message');
            $table->foreignId('created_by')->nullable()->after('notas')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'plan', 'mrr', 'storage_limit', 'storage_used',
                'max_users', 'maintenance_mode', 'maintenance_message',
                'notas', 'created_by',
            ]);
        });
    }
};