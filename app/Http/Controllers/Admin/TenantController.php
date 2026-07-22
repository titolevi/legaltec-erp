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
        $tenants = Tenant::withCount('users')->orderBy('name')->paginate(20);
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|alpha_dash|unique:tenants',
            'ruc' => 'nullable|string|size:11|unique:tenants',
            'email' => 'nullable|email|max:255',
        ]);

        $data['status'] = 'active';
        $data['plan'] = 'trial';
        $tenant = Tenant::create($data);

        return redirect()->route('admin.tenants')
            ->with('message', "✅ Tenant {$tenant->name} creado exitosamente.");
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|alpha_dash|unique:tenants,slug,' . $tenant->id,
            'ruc' => 'nullable|string|size:11|unique:tenants,ruc,' . $tenant->id,
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,suspended,trial,cancelled',
            'plan' => 'required|in:trial,starter,professional,enterprise',
        ]);

        $tenant->update($data);

        return redirect()->route('admin.tenants')
            ->with('message', "✅ Tenant {$tenant->name} actualizado.");
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->update(['status' => 'cancelled']);
        return redirect()->route('admin.tenants')
            ->with('message', "⛔ Tenant {$tenant->name} desactivado.");
    }

    public function enter(Tenant $tenant)
    {
        AuditLog::create([
            'tenant_id' => $tenant->id,
            'user_id' => auth()->id(),
            'accion' => 'impersonacion',
            'descripcion' => "Super admin ingresó al tenant: {$tenant->name}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session(['impersonating_tenant_id' => $tenant->id]);
        return redirect()->route('admin.dashboard')
            ->with('message', "🔀 Has entrado a: {$tenant->name}");
    }

    public function exit()
    {
        session()->forget('impersonating_tenant_id');
        return redirect()->route('admin.dashboard')
            ->with('message', "🔀 Has salido del tenant.");
    }
}