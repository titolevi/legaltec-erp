<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'caja_id',
        'cliente_id',
        'asunto_id',
        'codigo_asunto',
        'fecha_diligencia',
        'detalle',
        'distrito',
        'facturable',
        'monto',
        'divisa',
        'tipo_transaccion',
        'titular_cuenta',
        'numero_cuenta',
        'banco',
        'ejecutado_por',
        'autorizador_id',
        'estado',
        'usuario_id',
        'observaciones',
        'campos_extra',
        'tenant_id',
    ];

    protected function casts(): array
    {
        return [
            'fecha_diligencia' => 'date',
            'facturable' => 'boolean',
            'monto' => 'decimal:2',
            'campos_extra' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
        static::creating(function ($model) {
            if (auth()->check() && !$model->tenant_id) {
                $model->tenant_id = auth()->user()->tenant_id;
            }
        });
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function asunto(): BelongsTo
    {
        return $this->belongsTo(Asunto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function autorizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'autorizador_id');
    }

    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeAprobados($query)
    {
        return $query->where('estado', 'aprobado');
    }

    public function scopePorAutorizar($query, $userId)
    {
        return $query->where('autorizador_id', $userId)
                     ->where('estado', 'pendiente');
    }
}