<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * - super_admin: ve todo (sin filtro)
     * - support_agent: ve datos de los tenants que tiene asignados
     * - usuarios normales: solo ven su propio tenant
     *
     * ⚠️ IMPORTANTE: NO se aplica al modelo User para evitar bucles infinitos
     */
    public function apply(Builder $builder, Model $model): void
    {
        // ⛔ No aplicar al modelo User (evita bucle con auth)
        if ($model instanceof \App\Models\User) {
            return;
        }

        // ⛔ No aplicar en consola, tests o cuando no hay usuario autenticado
        if (app()->runningInConsole() || !auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super admin ve todo, pero puede impersonar un tenant
        if ($user->esSuperAdmin()) {
            $impersonatingId = session('impersonating_tenant_id');
            if ($impersonatingId) {
                $builder->where('tenant_id', $impersonatingId);
            }
            return;
        }

        // Support agent: ve los tenants asignados
        if ($user->esSupport()) {
            $tenantIds = $user->tenantsAsignados()->pluck('tenants.id');
            if ($tenantIds->isNotEmpty()) {
                $builder->whereIn('tenant_id', $tenantIds);
            }
            return;
        }

        // Usuario normal: solo su tenant
        if ($user->tenant_id) {
            $builder->where('tenant_id', $user->tenant_id);
        }
    }
}