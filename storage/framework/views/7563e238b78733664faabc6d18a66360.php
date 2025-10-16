<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Gerenciamento de Demandas')); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(session('success')): ?>
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
             <?php if(session('error')): ?>
                <div class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="<?php echo e(route('demandas.create')); ?>" class="bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            Lançar Nova Demanda
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th> 
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $demandas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demanda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="<?php echo e(route('demandas.show', $demanda->id)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-semibold">
                                                <?php echo e($demanda->titulo); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($demanda->status); ?></td>

                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($demanda->tipo == 'urgente'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 text-purple-800 dark:bg-purple-700 dark:text-purple-100">
                                                    Urgente (App)
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-100">
                                                    Normal
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($demanda->is_priority && $demanda->tipo != 'urgente'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100">Prioridade</span>
                                            <?php else: ?>
                                                
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Padrão</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap"><?php echo e($demanda->created_at->format('d/m/Y H:i')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-4 justify-end">
                                            <a href="<?php echo e(route('demandas.show', $demanda->id)); ?>" class="text-green-600 hover:text-green-900 dark:text-green-400">Ver Detalhes</a>
                                            <a href="<?php echo e(route('demandas.edit', $demanda->id)); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                                            <form action="<?php echo e(route('demandas.destroy', $demanda->id)); ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta demanda?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="6" class="px-6 py-4 text-center dark:text-gray-400">Nenhuma demanda lançada.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/demandas/index.blade.php ENDPATH**/ ?>