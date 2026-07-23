<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenantByDomain
{
    public function handle(Request $request, Closure $next): Response
    {
        // Detectar subdominio
        $host = $request->getHost();
        $parts = explode('.', $host);

        // Si hay subdominio (ej: viera.legaltec.pe)
        if (count($parts) >= 3) {
            $subdomain = $parts[0];

            // Buscar tenant por slug (subdominio)
            $tenant = Tenant::where('slug', $subdomain)
                ->where('activo', true)
                ->first();

            if ($tenant) {
                // Guardar tenant en session para que TenantScope lo use
                session(['impersonating_tenant_id' => $tenant->id]);
                session(['current_tenant' => $tenant->slug]);
            }
        } else {
            // Sin subdominio = legaltec.pe = tenant padre
            // Solo limpiar si no hay un tenant padre
            if (!session('is_legaltec')) {
                session()->forget(['impersonating_tenant_id', 'current_tenant']);
            }
        }

        return $next($request);
    }
}