<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'ruc',
        'email',
        'logo',
        'activo',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'config' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function supportAgents()
    {
        return $this->belongsToMany(User::class, 'support_assignments');
    }

    public function modules()
    {
        return $this->hasMany(\App\Models\TenantModule::class);
    }

    public function usage()
    {
        return $this->hasMany(\App\Models\TenantUsage::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\TenantInvoice::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(\App\Models\AuditLog::class);
    }

    public function cajas()
    {
        return $this->hasMany(\App\Models\Caja::class);
    }

    public function estaSuspendido(): bool
    {
        return $this->status === 'suspended';
    }

    public function moduloActivo(string $slug): bool
    {
        return $this->modules()->where('module_slug', $slug)->where('activo', true)->exists();
    }
}