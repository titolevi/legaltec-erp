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
     */
    public function apply(Builder $builder, Model $model): void
    {
        // No aplicar en consola, tests o cuando no hay usuario autenticado
        if (!app()->runningInConsole() && auth()->check()) {
            $user = auth()->user();

            // Super admin ve todo
            if ($user->esSuperAdmin()) {
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
}