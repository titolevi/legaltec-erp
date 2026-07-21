<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantUsage extends Model
{
    protected $table = 'tenant_usage';

    protected $fillable = [
        'tenant_id', 'periodo', 'usuarios_activos', 'tickets_creados',
        'tickets_aprobados', 'almacenamiento_mb', 'facturas_emitidas', 'api_calls',
    ];

    protected function casts(): array
    {
        return ['periodo' => 'date'];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

class SystemHealth extends Model
{
    protected $fillable = ['tenant_id', 'tipo', 'estado', 'valor', 'mensaje'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeCriticos($query)
    {
        return $query->where('estado', 'critical');
    }
}

class TenantInvoice extends Model
{
    protected $fillable = [
        'tenant_id', 'periodo', 'monto', 'moneda',
        'estado', 'fecha_emision', 'fecha_pago', 'metodo_pago', 'notas',
    ];

    protected function casts(): array
    {
        return [
            'periodo' => 'date',
            'fecha_emision' => 'datetime',
            'fecha_pago' => 'datetime',
            'monto' => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}