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
            <?php echo e(__('Cadastrar Novo Veículo')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="<?php echo e(route('veiculos.store')); ?>">
                        <?php echo csrf_field(); ?> 
                        
                        <div>
                            <label for="placa" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Placa</label>
                            <input id="placa" name="placa" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="modelo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Modelo</label>
                            <input id="modelo" name="modelo" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" required />
                        </div>

                        <div class="mt-4">
                            <label for="marca" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marca</label>
                            <input id="marca" name="marca" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div class="mt-4">
                            <label for="ano" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ano</label>
                            <input id="ano" name="ano" type="number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="<?php echo e(route('veiculos.index')); ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <button type="submit" class="ms-4 bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Salvar Veículo
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
<?php endif; ?><?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/veiculos/create.blade.php ENDPATH**/ ?>