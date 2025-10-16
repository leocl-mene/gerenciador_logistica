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
            Detalhes da Demanda: <?php echo e($demanda->titulo); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações Gerais</h3>
                
                <p><strong>Status:</strong> <?php echo e($demanda->status); ?></p>
                <p><strong>Tipo:</strong> <?php echo e($demanda->tipo ?? 'Normal'); ?></p>
                <p><strong>Criada por:</strong> <?php echo e($demanda->secretaria->name); ?> em <?php echo e($demanda->created_at->format('d/m/Y H:i')); ?></p>
                <?php if($demanda->veiculo): ?>
                    <p><strong>Veículo Utilizado:</strong> <?php echo e($demanda->veiculo->modelo); ?> (<?php echo e($demanda->veiculo->placa); ?>)</p>
                <?php endif; ?>
                
                <hr class="my-3 border-gray-700">

                <?php if($demanda->motoboy): ?>
                    <p><strong>Motoboy:</strong> <?php echo e($demanda->motoboy->name); ?></p>
                    <p><strong>Aceita em:</strong> <?php echo e($demanda->data_aceite ? \Carbon\Carbon::parse($demanda->data_aceite)->format('d/m/Y H:i') : 'N/A'); ?></p>

                    
                    <?php if($demanda->km_inicial): ?>
                        <p class="mt-3"><strong>KM Inicial Digitado:</strong> <?php echo e(number_format($demanda->km_inicial, 0, ',', '.')); ?> km</p>
                    <?php endif; ?>
                    <?php if($demanda->km_final): ?>
                        <p><strong>KM Final Digitado:</strong> <?php echo e(number_format($demanda->km_final, 0, ',', '.')); ?> km</p>
                        <p class="font-bold text-lg text-indigo-500 dark:text-indigo-400">
                            <strong>KM Rodado (Calculado):</strong> <?php echo e(number_format($demanda->km_final - $demanda->km_inicial, 0, ',', '.')); ?> km
                        </p>
                    <?php endif; ?>
                    
                <?php endif; ?>
                
                <?php if($demanda->descricao): ?>
                    <p class="mt-4"><strong>Descrição:</strong><br><?php echo e($demanda->descricao); ?></p>
                <?php endif; ?>

                <hr class="my-6 border-gray-600">

                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Percurso Completo</h3>
                <ol class="list-decimal list-inside space-y-2">
                    <?php $__currentLoopData = $demanda->percursos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $percurso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($percurso->endereco); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ol>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Fotos do Odômetro</h3>
                <?php if($demanda->fotosKm): ?>
                    <div>
                        <p class="font-semibold">KM Inicial:</p>
                        <img src="<?php echo e($demanda->fotosKm->foto_url_inicio); ?>" alt="Foto KM Inicial" class="mt-2 rounded-lg w-full">
                    </div>
                    <?php if($demanda->fotosKm->foto_url_final): ?>
                        <div class="mt-4">
                            <p class="font-semibold">KM Final:</p>
                            <img src="<?php echo e($demanda->fotosKm->foto_url_final); ?>" alt="Foto KM Final" class="mt-2 rounded-lg w-full">
                        </div>
                    <?php else: ?>
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Aguardando foto do KM final.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma foto de KM enviada para esta demanda.</p>
                <?php endif; ?>
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
<?php endif; ?><?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/demandas/show.blade.php ENDPATH**/ ?>