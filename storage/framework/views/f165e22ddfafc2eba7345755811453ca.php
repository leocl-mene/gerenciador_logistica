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
            <?php echo e(__('Relatório de Utilização de Veículos')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 mb-6">
                <form action="<?php echo e(route('settings.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <label for="preco_gasolina" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Preço Atual do Litro da Gasolina (R$)</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="preco_gasolina" 
                            id="preco_gasolina" 
                            value="<?php echo e(old('preco_gasolina', number_format((float)($preco_gasolina ?? 0), 2, '.', ''))); ?>" 
                            class="w-full md:w-1/4 rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                            required
                        >
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Salvar Preço</button>
                    </div>
                    <?php $__errorArgs = ['preco_gasolina'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if(session('success')): ?>
                        <p class="text-sm text-green-600 dark:text-green-400 mt-2"><?php echo e(session('success')); ?></p>
                    <?php endif; ?>
                </form>
            </div>

            
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="<?php echo e(route('relatorios.generate')); ?>" method="GET" id="report-form">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="data_inicio" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data de Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="<?php echo e($data_inicio ?? ''); ?>" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="data_fim" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data Final</label>
                            <input type="date" name="data_fim" id="data_fim" value="<?php echo e($data_fim ?? ''); ?>" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    
                    
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Selecione um ou mais veículos</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 p-4 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 max-h-60 overflow-y-auto">
                            <?php $__currentLoopData = $veiculos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $veiculo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Adicionando um fallback para o consumo caso o accessor retorne algo inválido
                                    $consumo = method_exists($veiculo, 'getConsumoPadraoAttribute') ? $veiculo->consumo_padrao : 'N/A';
                                ?>
                                <label class="flex items-center space-x-2 cursor-pointer p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    
                                    <input type="checkbox" name="veiculos_ids[]" value="<?php echo e($veiculo->id); ?>" 
                                            class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            
                                            <?php if(in_array($veiculo->id, $veiculos_selecionados_ids ?? [])): echo 'checked'; endif; ?>>
                                    <span class="text-sm text-gray-800 dark:text-gray-200 font-medium whitespace-nowrap">
                                        <?php echo e($veiculo->placa); ?> 
                                        <span class="text-xs text-gray-500 dark:text-gray-400">(<?php echo e(number_format((float)$consumo, 1, ',', '.')); ?> km/L)</span>
                                    </span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($errors->has('veiculos_ids')): ?>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2 col-span-full"><?php echo e($errors->first('veiculos_ids')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-end gap-2 mt-6">
                        <button type="submit" name="action" value="preview" class="justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            Pré-visualizar
                        </button>
                        <button type="submit" name="action" value="download" class="justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            Baixar Excel
                        </button>
                    </div>
                </form>
            </div>

            
            <?php if(isset($demandas)): ?>
            
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Pré-visualização do Relatório
                        <span class="text-base font-normal text-gray-600 dark:text-gray-400 block sm:inline">
                             | Período de **<?php echo e(\Carbon\Carbon::parse($data_inicio)->format('d/m/Y')); ?>** a **<?php echo e(\Carbon\Carbon::parse($data_fim)->format('d/m/Y')); ?>**
                        </span>
                    </h3>
                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Veículo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Percurso</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KM Inicial</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KM Final</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">KM Rodado</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Custo (R$)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motorista</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                                <?php $__empty_1 = true; $__currentLoopData = $demandas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demanda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo e(\Carbon\Carbon::parse($demanda->data_finalizacao)->format('d/m/Y')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold"><?php echo e($demanda->veiculo->placa ?? 'N/A'); ?></td>
                                        
                                        
                                        <td class="px-6 py-4 text-sm">
                                            <?php if($demanda->tipo === 'urgente' && $demanda->gpsTracks->count() > 0): ?>
                                                Rota Urgente (Gravada via GPS)
                                            <?php else: ?>
                                                <?php echo e($demanda->percursos->pluck('endereco')->join(' &rarr; ')); ?>

                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm"><?php echo e(number_format($demanda->km_inicial, 0, ',', '.')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm"><?php echo e(number_format($demanda->km_final, 0, ',', '.')); ?></td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-sm text-blue-600 dark:text-blue-400">
                                            <?php echo e(number_format($demanda->km_final - $demanda->km_inicial, 0, ',', '.')); ?>

                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 dark:text-red-400 font-semibold">
                                            <?php
                                                $consumo = $demanda->veiculo->consumo_padrao ?? 0;
                                                $kmRodado = $demanda->km_final - $demanda->km_inicial;
                                                $custo = ($consumo > 1) ? ($kmRodado / $consumo) * (float)$preco_gasolina : 0;
                                            ?>
                                            <?php if($custo > 0): ?>
                                                R$ <?php echo e(number_format($custo, 2, ',', '.')); ?>

                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?php echo e($demanda->motoboy->name ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Nenhuma demanda finalizada encontrada para os veículos selecionados no período.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            
                            <tfoot class="bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right font-extrabold uppercase text-gray-900 dark:text-gray-100">Totais no Período:</td>
                                    
                                    <td class="px-6 py-4 text-center font-extrabold text-lg text-green-600 dark:text-green-400">
                                        <?php echo e(number_format($demandas->sum(function($d) { return $d->km_final - $d->km_inicial; }), 0, ',', '.')); ?> km
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center font-extrabold text-lg text-red-600 dark:text-red-400">
                                        R$ <?php echo e(number_format($demandas->sum(function($d) use ($preco_gasolina) {
                                            $consumo = $d->veiculo->consumo_padrao ?? 0;
                                            if ($consumo > 1) {
                                                return (($d->km_final - $d->km_inicial) / $consumo) * (float)$preco_gasolina;
                                            }
                                            return 0;
                                        }), 2, ',', '.')); ?>

                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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
<?php endif; ?><?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/relatorios/index.blade.php ENDPATH**/ ?>