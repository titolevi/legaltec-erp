<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginal5194778a3a7b899dcee5619d0610f5cf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alert','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $attributes = $__attributesOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__attributesOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf)): ?>
<?php $component = $__componentOriginal5194778a3a7b899dcee5619d0610f5cf; ?>
<?php unset($__componentOriginal5194778a3a7b899dcee5619d0610f5cf); ?>
<?php endif; ?>
<div class="mb-6">
 <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100"> Panel de Tenants</h1>
 <p class="text-gray-500 dark:text-gray-400">Gestión de Tenants — Legaltec SaaS</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
 <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tenants registrados</h2>
 <a href="<?php echo e(route('admin.tenants.create')); ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
 + Nuevo Tenant
 </a>
 </div>

 <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
 <thead class="bg-gray-50 dark:bg-gray-900">
 <tr>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tenant</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Plan</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Usuarios</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">MRR</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
 <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
 <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($tenant->name); ?></td>
 <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo e($tenant->slug); ?></td>
 <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo e(ucfirst($tenant->plan)); ?></td>
 <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400"><?php echo e($tenant->users_count); ?> / <?php echo e($tenant->max_users); ?></td>
 <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">S/ <?php echo e(number_format($tenant->mrr, 2)); ?></td>
 <td class="px-4 py-3">
 <?php
 $statusColors = [
 'active' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
 'suspended' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
 'trial' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
 'cancelled' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
 ];
 $color = $statusColors[$tenant->status] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200';
 ?>
 <span class="px-2 py-1 text-xs rounded-full <?php echo e($color); ?>">
 <?php echo e(ucfirst($tenant->status)); ?>

 </span>
 </td>
 <td class="px-4 py-3 text-sm space-x-2">
 <a href="<?php echo e(route('admin.tenants.enter', $tenant)); ?>"
 class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-xs">
 Entrar
 </a>
 <a href="<?php echo e(route('admin.tenants.edit', $tenant)); ?>"
 class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition text-xs">
 Editar Editar
 </a>
 </td>
 </tr>
 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
 <tr>
 <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
 <div class="text-4xl mb-4"></div>
 <p>No hay tenants registrados aún</p>
 </td>
 </tr>
 <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
 </tbody>
 </table>
 <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tenants->hasPages()): ?>
 <div class="p-4 border-t border-gray-200 dark:border-gray-700">
 <?php echo e($tenants->links()); ?>

 </div>
 <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\legaltec-erp\resources\views/admin/tenants/index.blade.php ENDPATH**/ ?>