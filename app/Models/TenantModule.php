<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantModule extends Model
{
    protected $fillable = [
        'tenant_id', 'module_slug', 'module_name', 'activo',
        'fecha_activacion', 'precio_mensual', 'config',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_activacion' => 'datetime',
            'precio_mensual' => 'decimal:2',
            'config' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}