<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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