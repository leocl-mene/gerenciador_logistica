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
            Associar Veículos para: <span class="text-primaria"><?php echo e($motoboy->name); ?></span>
        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="<?php echo e(route('motoboys.veiculos.salvar', $motoboy->id)); ?>">
                        <?php echo csrf_field(); ?>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Selecione os veículos que este motoboy pode utilizar:
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            <?php $__empty_1 = true; $__currentLoopData = $todosVeiculos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veiculo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <label class="flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" name="veiculos_ids[]" value="<?php echo e($veiculo->id); ?>"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           <?php if(in_array($veiculo->id, $veiculosAtuais)): ?> checked <?php endif; ?>>
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-300">
                                        <?php echo e($veiculo->modelo); ?> - <?php echo e($veiculo->placa); ?>

                                    </span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p>Nenhum veículo cadastrado. Cadastre um veículo primeiro.</p>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="<?php echo e(route('motoboys.index')); ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md">
                                Cancelar
                            </a>
                            <button type="submit" class="ms-4 bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Salvar Associações
                            </button>
                        </div>
                    </form>
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
<?php endif; ?><?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/motoboys/gerenciar-veiculos.blade.php ENDPATH**/ ?>