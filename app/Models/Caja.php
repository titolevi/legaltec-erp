<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id', 'nombre', 'slug', 'descripcion', 'tipo',
        'moneda', 'monto_maximo', 'require_authorization', 'color', 'icono', 'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'monto_maximo' => 'decimal:2',
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function autorizadores(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'caja_autorizadores')
            ->withPivot('limite_aprobacion')
            ->withTimestamps();
    }

    public function cajeros(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'caja_cajeros')
            ->withTimestamps();
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function esMovilidad(): bool
    {
        return $this->tipo === 'movilidad';
    }
}