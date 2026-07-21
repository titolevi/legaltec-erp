<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureFlag extends Model
{
    protected $fillable = ['slug', 'nombre', 'descripcion', 'activo_global'];

    protected function casts(): array
    {
        return ['activo_global' => 'boolean'];
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_feature_flags')
            ->withPivot('activo')
            ->withTimestamps();
    }

    public function estaActivoPara(Tenant $tenant): bool
    {
        if ($this->activo_global) return true;
        $pivot = $this->tenants()->where('tenant_id', $tenant->id)->first();
        return $pivot && $pivot->pivot->activo;
    }
}