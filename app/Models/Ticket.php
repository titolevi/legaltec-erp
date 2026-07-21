<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'cliente_id',
        'asunto_id',
        'codigo_asunto',
        'fecha_diligencia',
        'detalle',
        'distrito',
        'facturable',
        'monto',
        'moneda',
        'ejecutado_por',
        'autorizador_id',
        'estado',
        'usuario_id',
        'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha_diligencia' => 'date',
            'facturable' => 'boolean',
            'monto' => 'decimal:2',
        ];
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