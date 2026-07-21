<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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