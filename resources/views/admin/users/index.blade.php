<?php
/* @var \Illuminate\View\Factory $this */
/* @var \App\Models\User $users */
/* @var \App\Models\Tenant $tenants */
?>
@extends('layouts.app')
@section('content')
<?php echo view('components.alert')->render(); ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Gestion de Usuarios</h1>
    <p class="text-gray-500 dark:text-gray-400">Administra usuarios y roles del sistema</p>
</div>

<?php if(auth()->user()->esSuperAdmin() && $tenants->isNotEmpty()): ?>
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-4">
        <select name="tenant_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los tenants</option>
            <?php foreach($tenants as $t): ?>
            <option value="<?php echo $t->id; ?>" <?php echo request('tenant_id') == $t->id ? 'selected' : ''; ?>><?php echo $t->name; ?></option>
            <?php endforeach; ?>
        </select>
        <select name="rol" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los roles</option>
            <?php foreach(['super_admin'=>'Super Admin','support_admin'=>'Support Admin','support_agent'=>'Support Agent','admin'=>'Admin','autorizador'=>'Autorizador','cajero'=>'Cajero','usuario'=>'Usuario'] as $val => $label): ?>
            <option value="<?php echo $val; ?>" <?php echo request('rol') == $val ? 'selected' : ''; ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filtrar</button>
        <a href="<?php echo route('admin.users'); ?>" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm hover:bg-gray-400 dark:hover:bg-gray-500">Limpiar</a>
    </form>
</div>
<?php endif; ?>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Usuarios registrados</h2>
        <a href="<?php echo route('admin.users.create'); ?>" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-700">+ Nuevo Usuario</a>
    </div>

    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rol</th>
                <?php if(auth()->user()->esSuperAdmin()): ?>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tenant</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Ultimo acceso</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <?php if(count($users) > 0): ?>
            <?php
            $roleColors = [
                'super_admin' => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
                'support_admin' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                'support_agent' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
                'admin' => 'bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200',
                'autorizador' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
                'cajero' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
                'usuario' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
            ];
            ?>
            <?php foreach($users as $u): ?>
            <?php
            $rolColor = $roleColors[$u->rol] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
            $estadoColor = $u->activo ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200';
            ?>
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo $u->name; ?></td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo $u->email; ?></td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full <?php echo $rolColor; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $u->rol)); ?>
                    </span>
                </td>
                <?php if(auth()->user()->esSuperAdmin()): ?>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo $u->tenant?->name ?? '-'; ?></td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full <?php echo $estadoColor; ?>">
                        <?php echo $u->activo ? 'Activo' : 'Inactivo'; ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo $u->last_login_at ? $u->last_login_at->format('d/m/Y H:i') : 'Nunca'; ?></td>
                <td class="px-4 py-3 text-sm">
                    <a href="<?php echo route('admin.users.edit', $u); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">Editar</a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="<?php echo auth()->user()->esSuperAdmin() ? 7 : 4; ?>" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-2">-</div>
                    <p>No hay usuarios registrados</p>
                </td>
            </tr>
            <?php endif; ?>
        <?php endif; ?>
        </tbody>
    </table>
    <?php if($users->hasPages()): ?>
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <?php echo $users->links(); ?>
    </div>
    <?php endif; ?>
</div>
@endsection