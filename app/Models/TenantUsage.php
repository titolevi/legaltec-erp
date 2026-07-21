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