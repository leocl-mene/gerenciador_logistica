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
            <?php echo e('Lançar Nova Demanda'); ?>

        </h2>
     <?php $__env->endSlot(); ?>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <?php if($errors->any()): ?>
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Opa!</strong>
                            <span class="block sm:inline">Algo deu errado. Verifique os campos abaixo.</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('demandas.store')); ?>">
                        <?php echo csrf_field(); ?>

                        <div>
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'titulo','value' => 'Título da Demanda']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'titulo','value' => 'Título da Demanda']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                            <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['id' => 'titulo','class' => 'block mt-1 w-full','type' => 'text','name' => 'titulo','value' => old('titulo'),'required' => true,'autofocus' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'titulo','class' => 'block mt-1 w-full','type' => 'text','name' => 'titulo','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(old('titulo')),'required' => true,'autofocus' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                        </div>

                        <div class="mt-4">
                            <?php if (isset($component)) { $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.input-label','data' => ['for' => 'descricao','value' => 'Descrição (Opcional)']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('input-label'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['for' => 'descricao','value' => 'Descrição (Opcional)']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $attributes = $__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__attributesOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581)): ?>
<?php $component = $__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581; ?>
<?php unset($__componentOriginale3da9d84bb64e4bc2eeebaafabfb2581); ?>
<?php endif; ?>
                            <textarea id="descricao" name="descricao" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"><?php echo e(old('descricao')); ?></textarea>
                        </div>

                        <div class="block mt-4">
                            <label for="is_priority" class="inline-flex items-center">
                                <input id="is_priority" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" name="is_priority" value="1" <?php if(old('is_priority')): ?> checked <?php endif; ?>>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Demanda Urgente</span>
                            </label>
                        </div>

                        <hr class="my-6 border-gray-300 dark:border-gray-700">

                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Percurso da Demanda</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Pelo menos um ponto de partida e um de chegada são obrigatórios.</p>
                        <div id="percursos-container" class="mt-4 space-y-4">
                            
                            <div class="flex items-center gap-2 relative">
                                <span class="font-bold text-gray-100">1.</span>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['class' => 'block w-full','type' => 'text','name' => 'percursos[]','placeholder' => 'Ponto de Partida (Ex: Endereço da Empresa)','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block w-full','type' => 'text','name' => 'percursos[]','placeholder' => 'Ponto de Partida (Ex: Endereço da Empresa)','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            </div>
                             
                            <div class="flex items-center gap-2 relative">
                                <span class="font-bold text-gray-100">2.</span>
                                <?php if (isset($component)) { $__componentOriginal18c21970322f9e5c938bc954620c12bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal18c21970322f9e5c938bc954620c12bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.text-input','data' => ['class' => 'block w-full','type' => 'text','name' => 'percursos[]','placeholder' => 'Ponto de Chegada/Destino','required' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('text-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'block w-full','type' => 'text','name' => 'percursos[]','placeholder' => 'Ponto de Chegada/Destino','required' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $attributes = $__attributesOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__attributesOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal18c21970322f9e5c938bc954620c12bb)): ?>
<?php $component = $__componentOriginal18c21970322f9e5c938bc954620c12bb; ?>
<?php unset($__componentOriginal18c21970322f9e5c938bc954620c12bb); ?>
<?php endif; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add-percurso" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-white">+ Adicionar Parada Intermediária</button>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="<?php echo e(route('demandas.index')); ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Cancelar</a>
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'ms-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'ms-4']); ?>Salvar Demanda <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('percursos-container');
            const addButton = document.getElementById('add-percurso');
            let percursoCount = 2;

            // --- LÓGICA DO NOMINATIM ---
            let debounceTimer;

            // Função que busca endereços no Nominatim
            async function searchAddress(query, inputElement) {
                if (query.length < 3) {
                    clearResults(inputElement);
                    return;
                }
                // URL da API pública do Nominatim (com parâmetros para o Brasil)
                const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&countrycodes=br&addressdetails=1`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    displayResults(data, inputElement);
                } catch (error) {
                    console.error('Erro ao buscar endereço:', error);
                }
            }

            // Função que exibe os resultados da busca
            function displayResults(results, inputElement) {
                clearResults(inputElement);
                const resultsContainer = document.createElement('div');
                resultsContainer.className = 'autocomplete-results absolute bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md mt-1 w-full z-10 max-h-48 overflow-y-auto';

                if (results.length === 0) {
                    resultsContainer.innerHTML = `<div class="p-2 text-gray-500 dark:text-gray-300">Nenhum resultado encontrado.</div>`;
                } else {
                    results.forEach(result => {
                        const item = document.createElement('div');
                        item.className = 'p-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer';
                        item.textContent = result.display_name;
                        item.onclick = () => {
                            inputElement.value = result.display_name;
                            clearResults(inputElement);
                        };
                        resultsContainer.appendChild(item);
                    });
                }
                inputElement.parentNode.appendChild(resultsContainer);
            }

            // Função que limpa a lista de resultados
            function clearResults(inputElement) {
                const parent = inputElement.parentNode;
                const resultsContainer = parent.querySelector('.autocomplete-results');
                if (resultsContainer) {
                    parent.removeChild(resultsContainer);
                }
            }

            // Função que "escuta" a digitação nos campos de percurso
            function setupInputListener(inputElement) {
                inputElement.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        searchAddress(inputElement.value, inputElement);
                    }, 300); // Espera 300ms após o usuário parar de digitar
                });
                 // Limpa os resultados se o usuário clicar fora
                document.addEventListener('click', (e) => {
                    if (!inputElement.parentNode.contains(e.target)) {
                         clearResults(inputElement);
                    }
                });
            }
            // --- FIM DA LÓGICA DO NOMINATIM ---


            function updateNumbers() {
                percursoCount = 0;
                container.querySelectorAll('div.flex').forEach(item => {
                    percursoCount++;
                    const span = item.querySelector('span.font-bold');
                    span.textContent = `${percursoCount}.`;
                    const input = item.querySelector('input');
                    if (percursoCount > 2) {
                        input.placeholder = `Parada ${percursoCount - 1}`;
                    }
                });
            }

            // Inicializa a escuta nos campos de endereço que já existem
            document.querySelectorAll('input[name="percursos[]"]').forEach(setupInputListener);

            // Lógica para adicionar novos campos de percurso
            addButton.addEventListener('click', function () {
                percursoCount = container.children.length + 1;
                
                const newPercursoWrapper = document.createElement('div');
                newPercursoWrapper.classList.add('flex', 'items-center', 'gap-2', 'relative'); // Adicionado 'relative' para o posicionamento dos resultados

                const newPercursoHtml = `
                    <span class="font-bold text-gray-800 dark:text-gray-200">${percursoCount}.</span>
                    <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Parada ${percursoCount - 1}" required />
                    <button type="button" class="remove-percurso text-red-500 hover:text-red-700 p-1 shrink-0">Remover</button>
                `;
                newPercursoWrapper.innerHTML = newPercursoHtml;
                container.appendChild(newPercursoWrapper);

                // IMPORTANTE: Adiciona a escuta de digitação no novo campo criado
                setupInputListener(newPercursoWrapper.querySelector('input'));
            });

            // Lógica para remover um campo de percurso
            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-percurso')) {
                    e.target.parentElement.remove();
                    updateNumbers(); // Renumera os itens restantes
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH C:\Users\Ti\Desktop\gerenciador_logistica\gerenciador_logistica\resources\views/secretaria/demandas/create.blade.php ENDPATH**/ ?>