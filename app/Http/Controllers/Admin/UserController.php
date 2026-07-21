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
        $authUser = auth()->user();

        if ($authUser->esSuperAdmin()) {
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
            abort_unless($authUser->esAdminTenant(), 403);
            $users = User::where('tenant_id', $authUser->tenant_id)
                ->orderBy('name')
                ->paginate(20);
            $tenants = collect();
        }

        return view('admin.users.index', compact('users', 'tenants'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $roles = $this->getAvailableRoles($authUser);
        $tenants = ($authUser->esSuperAdmin())
            ? Tenant::where('status', 'active')->orderBy('name')->get()
            : collect();

        return view('admin.users.create', compact('roles', 'tenants'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::defaults()],
            'rol' => 'required|string|in:' . implode(',', $this->getAvailableRoles($authUser)->keys()->toArray()),
            'telefono' => 'nullable|string|max:20',
        ];

        if ($authUser->esSuperAdmin()) {
            $rules['tenant_id'] = 'nullable|exists:tenants,id';
        }

        $data = $request->validate($rules);

        if (!$authUser->esSuperAdmin()) {
            $data['tenant_id'] = $authUser->tenant_id;
        }

        $data['password'] = Hash::make($data['password']);

        $newUser = User::create($data);

        $tenantName = $newUser->tenant?->name ?? 'Legaltec';
        return redirect()->route('admin.users')
            ->with('message', "✅ Usuario {$newUser->name} creado en {$tenantName} como {$newUser->rol}.");
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->esSuperAdmin() && $authUser->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $roles = $this->getAvailableRoles($authUser);
        $tenants = ($authUser->esSuperAdmin())
            ? Tenant::where('status', 'active')->orderBy('name')->get()
            : collect();

        return view('admin.users.edit', compact('user', 'roles', 'tenants'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->esSuperAdmin() && $authUser->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'rol' => 'required|string|in:' . implode(',', $this->getAvailableRoles($authUser)->keys()->toArray()),
            'telefono' => 'nullable|string|max:20',
            'activo' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::defaults()];
        }

        if ($authUser->esSuperAdmin()) {
            $rules['tenant_id'] = 'nullable|exists:tenants,id';
        }

        $data = $request->validate($rules);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (!$authUser->esSuperAdmin()) {
            unset($data['tenant_id']);
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('message', "✅ Usuario {$user->name} actualizado.");
    }

    public function destroy(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->esSuperAdmin() && $authUser->tenant_id !== $user->tenant_id) {
            abort(403);
        }

        if ($user->id === $authUser->id) {
            return back()->with('message', '❌ No puedes eliminarte a ti mismo.');
        }

        $user->update(['activo' => false]);

        return redirect()->route('admin.users')
            ->with('message', "⛔ Usuario {$user->name} desactivado.");
    }

    private function getAvailableRoles($authUser): \Illuminate\Support\Collection
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

        if ($authUser->esSuperAdmin()) {
            return $allRoles;
        }

        return $allRoles->only(['admin', 'autorizador', 'cajero', 'usuario']);
    }
}