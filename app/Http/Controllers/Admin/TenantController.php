<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $tenants = Tenant::withCount('users')
            ->with(['modules' => function ($q) { $q->where('activo', true); }])
            ->orderBy('name')->paginate(20);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:tenants,slug|alpha_dash',
            'ruc' => 'nullable|string|size:11|unique:tenants,ruc',
            'email' => 'nullable|email|max:255',
        ]);

        $tenant = Tenant::create($data);

        return redirect()->route('admin.tenants')
            ->with('message', "✅ Tenant {$tenant->name} creado exitosamente.");
    }

    public function edit(Tenant $tenant)
    {
        $this->authorizeAdmin();
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|alpha_dash|unique:tenants,slug,' . $tenant->id,
            'ruc' => 'nullable|string|size:11|unique:tenants,ruc,' . $tenant->id,
            'email' => 'nullable|email|max:255',
            'activo' => 'boolean',
        ]);

        $tenant->update($data);

        return redirect()->route('admin.tenants')
            ->with('message', "✅ Tenant {$tenant->name} actualizado.");
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorizeAdmin();
        $tenant->update(['activo' => false]);
        return redirect()->route('admin.tenants')
            ->with('message', "⛔ Tenant {$tenant->name} desactivado.");
    }

    public function enter(Tenant $tenant)
    {
        $this->authorizeAdmin();

        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->id(),
            'accion' => 'impersonacion.entrar',
            'descripcion' => "Admin entró al tenant: {$tenant->name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => [
                'tenant_name' => $tenant->name,
                'tenant_slug' => $tenant->slug,
            ],
        ]);

        session(['impersonating_tenant_id' => $tenant->id]);
        return redirect()->route('dashboard')
            ->with('message', "🔀 Has entrado a: {$tenant->name}");
    }

    public function exit()
    {
        $this->authorizeAdmin();

        $tenantId = session('impersonating_tenant_id');
        if ($tenantId) {
            $tenant = Tenant::find($tenantId);
            AuditLog::create([
                'tenant_id' => $tenantId,
                'user_id' => auth()->id(),
                'accion' => 'impersonacion.salir',
                'descripcion' => "Admin salió del tenant: " . ($tenant->name ?? 'N/A'),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        session()->forget('impersonating_tenant_id');
        return redirect()->route('admin.tenants')
            ->with('message', "🔙 Has vuelto al panel global de Legaltec.");
    }

    private function authorizeAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->esSuperAdmin(), 403);
    }
}