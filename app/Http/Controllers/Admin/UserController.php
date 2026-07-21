<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Super admin ve todos o filtra por tenant
        if ($user->esSuperAdmin()) {
            $query = User::with('tenant');
            if ($request->filled('tenant_id')) {
                $query->where('tenant_id', $request->tenant_id);
            }
            if ($request->filled('rol')) {
                $query->where('rol', $request->rol);
            }
            $users = $query->orderBy('name')->paginate(20);
            $tenants = Tenant::where('status', 'active')->orderBy('name')->get();
        } else {
            // Admin del tenant solo ve su tenant
            abort_unless($user->esAdminTenant(), 403);
            $users = User::where('tenant_id', $user->tenant_id)
                ->orderBy('name')
                ->paginate(20);
            $tenants = collect();
        }

        return view('admin.users.index', compact('users', 'tenants'));
    }

    public function create()
    {
        $user = auth()->user();

        $roles = $this->getAvailableRoles($user);
        $tenants = ($user->esSuperAdmin())
            ? Tenant::where('status', 'active')->orderBy('name')->get()
            : collect();

        return view('admin.users.create', compact('roles', 'tenants'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()],
            'rol' => 'required|string|in:' . implode(',', $this->getAvailableRoles($user)->keys()->toArray()),
            'telefono' => 'nullable|string|max:20',
        ];

        // Super admin puede asignar tenant; tenant admin usa el suyo
        if ($user->esSuperAdmin()) {
            $rules['tenant_id'] = 'nullable|exists:tenants,id';
        }

        $data = $request->validate($rules);

        if (!$user->esSuperAdmin()) {
            $data['tenant_id'] = $user->tenant_id;
        }

        $data['password'] = Hash::make($data['password']);

        $newUser = User::create($data);

        $tenantName = $newUser->tenant?->name ?? 'Legaltec';
        return redirect()->route('admin.users')
            ->with('message', "✅ Usuario {$newUser->name} creado en {$tenantName} como {$newUser->rol}.");
    }

    public function edit(User $targetUser)
    {
        $user = auth()->user();

        // Validar acceso
        if (!$user->esSuperAdmin() && $user->tenant_id !== $targetUser->tenant_id) {
            abort(403);
        }

        $roles = $this->getAvailableRoles($user);
        $tenants = ($user->esSuperAdmin())
            ? Tenant::where('status', 'active')->orderBy('name')->get()
            : collect();

        return view('admin.users.edit', compact('targetUser', 'roles', 'tenants'));
    }

    public function update(Request $request, User $targetUser)
    {
        $user = auth()->user();

        // Validar acceso
        if (!$user->esSuperAdmin() && $user->tenant_id !== $targetUser->tenant_id) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $targetUser->id,
            'rol' => 'required|string|in:' . implode(',', $this->getAvailableRoles($user)->keys()->toArray()),
            'telefono' => 'nullable|string|max:20',
            'activo' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::defaults()];
        }

        if ($user->esSuperAdmin()) {
            $rules['tenant_id'] = 'nullable|exists:tenants,id';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (!$user->esSuperAdmin()) {
            unset($data['tenant_id']);
        }

        $targetUser->update($data);

        return redirect()->route('admin.users')
            ->with('message', "✅ Usuario {$targetUser->name} actualizado.");
    }

    public function destroy(User $targetUser)
    {
        $user = auth()->user();

        if (!$user->esSuperAdmin() && $user->tenant_id !== $targetUser->tenant_id) {
            abort(403);
        }

        if ($targetUser->id === $user->id) {
            return back()->with('message', '❌ No puedes eliminarte a ti mismo.');
        }

        $targetUser->update(['activo' => false]);

        return redirect()->route('admin.users')
            ->with('message', "⛔ Usuario {$targetUser->name} desactivado.");
    }

    /**
     * Roles disponibles según el rol del usuario autenticado.
     */
    private function getAvailableRoles($user): \Illuminate\Support\Collection
    {
        $allRoles = collect([
            'super_admin' => 'Super Admin (Legaltec)',
            'support_admin' => 'Support Admin (Legaltec)',
            'support_agent' => 'Support Agent (Legaltec)',
            'admin' => 'Admin del Tenant',
            'autorizador' => 'Autorizador',
            'cajero' => 'Cajero',
            'usuario' => 'Usuario',
        ]);

        if ($user->esSuperAdmin()) {
            return $allRoles; // Super admin puede crear cualquier rol
        }

        // Admin del tenant solo puede crear roles dentro de su tenant
        return $allRoles->only(['admin', 'autorizador', 'cajero', 'usuario']);
    }
}