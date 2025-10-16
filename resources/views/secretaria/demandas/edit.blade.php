<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Editar Demanda' }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Bloco de Erros --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Opa!</strong>
                            <span class="block sm:inline">Algo deu errado. Verifique os campos abaixo.</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('demandas.update', $demanda->id) }}">
                        @csrf
                        @method('PUT')

                        {{-- TÍTULO, DESCRIÇÃO, PRIORIDADE --}}
                        <div>
                            <label for="titulo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Título da Demanda</label>
                            <input id="titulo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="titulo" value="{{ old('titulo', $demanda->titulo) }}" required autofocus />
                        </div>
                        <div class="mt-4">
                            <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição (Opcional)</label>
                            <textarea id="descricao" name="descricao" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('descricao', $demanda->descricao) }}</textarea>
                        </div>
                        <div class="block mt-4">
                            <label for="is_priority" class="inline-flex items-center">
                                <input id="is_priority" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" name="is_priority" value="1" @if(old('is_priority', $demanda->is_priority)) checked @endif>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Demanda Urgente</span>
                            </label>
                        </div>

                        <hr class="my-6 border-gray-600 dark:border-gray-700">

                        {{-- SELEÇÃO DE MOTOBOY E VEÍCULO (NOVO BLOCO) --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Atribuição</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Seleção de Motoboy --}}
                            <div>
                                <label for="motoboy_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Motoboy Responsável</label>
                                <select name="motoboy_id" id="motoboy_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Selecione um motoboy</option>
                                    @foreach($motoboys as $motoboy)
                                        <option value="{{ $motoboy->id }}" @selected(old('motoboy_id', $demanda->motoboy_id) == $motoboy->id)>
                                            {{ $motoboy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- Seleção de Veículo (Dinâmico) --}}
                            <div>
                                <label for="veiculo_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Veículo Utilizado</label>
                                <select name="veiculo_id" id="veiculo_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Selecione um motoboy primeiro</option>
                                    {{-- Pré-popula com os veículos do motoboy já selecionado (apenas para o load inicial) --}}
                                    @foreach($veiculosDoMotoboy as $veiculo)
                                        <option value="{{ $veiculo->id }}" @selected(old('veiculo_id', $demanda->veiculo_id) == $veiculo->id)>
                                            {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-6 border-gray-600 dark:border-gray-700">
                        {{-- FIM DO BLOCO DE ATRIBUIÇÃO --}}


                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Percurso da Demanda</h3>
                        <div id="percursos-container" class="mt-4 space-y-4">
                            @foreach($demanda->percursos as $index => $percurso)
                                <div class="flex items-center gap-2 relative">
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $index + 1 }}.</span>
                                    <input class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" 
                                           type="text" 
                                           name="percursos[]" 
                                           value="{{ old('percursos.' . $index, $percurso->endereco) }}" 
                                           placeholder="{{ $index == 0 ? 'Ponto de Partida' : ($index == (count($demanda->percursos) - 1) ? 'Ponto de Chegada/Destino' : 'Parada Intermediária') }}"
                                           required />
                                    @if($index > 1) 
                                        <button type="button" class="remove-percurso text-red-500 hover:text-red-700 p-1 shrink-0">Remover</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <button type="button" id="add-percurso" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-white transition">+ Adicionar Parada Intermediária</button>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('demandas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Cancelar</a>
                            <button type="submit" class="ms-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Atualizar Demanda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PARA DROPDOWNS DINÂMICOS E PERCURSOS --}}
    <script>
        // Objeto global para armazenar os veículos em cache
        let allVehicles = {}; 
        
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('percursos-container');
            const addButton = document.getElementById('add-percurso');
            
            const motoboySelect = document.getElementById('motoboy_id');
            const veiculoSelect = document.getElementById('veiculo_id');

            // Valores iniciais para manter o estado
            const initialMotoboyId = "{{ old('motoboy_id', $demanda->motoboy_id) }}";
            const initialVehicleId = "{{ old('veiculo_id', $demanda->veiculo_id) }}";


            // --- LÓGICA DE GERENCIAMENTO DE PERCURSOS ---

            // Renumera e ajusta placeholders
            function updateNumbers() {
                let currentCount = 0;
                container.querySelectorAll('div.flex').forEach(item => {
                    currentCount++;
                    const span = item.querySelector('span.font-bold');
                    if (span) span.textContent = `${currentCount}.`;

                    const input = item.querySelector('input');
                    if (currentCount > 2) {
                        input.placeholder = `Parada ${currentCount - 1}`;
                    } else if (currentCount === 2) {
                        input.placeholder = 'Ponto de Chegada/Destino';
                    } else if (currentCount === 1) {
                        input.placeholder = 'Ponto de Partida';
                    }
                    // Adiciona/Remove botão de remoção (a partir do 3º item)
                    let removeBtn = item.querySelector('.remove-percurso');
                    if (currentCount > 2) {
                        if (!removeBtn) {
                            removeBtn = document.createElement('button');
                            removeBtn.type = 'button';
                            removeBtn.className = 'remove-percurso text-red-500 hover:text-red-700 p-1 shrink-0';
                            removeBtn.textContent = 'Remover';
                            item.appendChild(removeBtn);
                        }
                    } else if (removeBtn) {
                        removeBtn.remove();
                    }
                });
            }


            // Lógica para adicionar novos campos de percurso
            addButton.addEventListener('click', function () {
                const newIndex = container.children.length + 1;
                
                const newPercursoWrapper = document.createElement('div');
                newPercursoWrapper.classList.add('flex', 'items-center', 'gap-2', 'relative'); 

                const newPercursoHtml = `
                    <span class="font-bold text-gray-800 dark:text-gray-200">${newIndex}.</span>
                    <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Parada ${newIndex - 1}" required />
                    <button type="button" class="remove-percurso text-red-500 hover:text-red-700 p-1 shrink-0">Remover</button>
                `;
                newPercursoWrapper.innerHTML = newPercursoHtml;
                container.appendChild(newPercursoWrapper);

                // IMPORTANTE: Adiciona a escuta de digitação no novo campo criado e atualiza os números
                setupInputListener(newPercursoWrapper.querySelector('input'));
                updateNumbers();
            });

            // Lógica para remover um campo de percurso
            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-percurso')) {
                    // Impede a remoção se houver apenas dois campos (partida e destino)
                    if (container.children.length > 2) {
                        // Limpa os resultados do autocomplete antes de remover
                        clearResults(e.target.parentElement.querySelector('input'));
                        e.target.parentElement.remove();
                        updateNumbers(); // Renumera os itens restantes
                    } else {
                        alert('É necessário ter pelo menos um ponto de partida e um de destino.');
                    }
                }
            });

            // --- LÓGICA DO NOMINATIM (Endereço Autocomplete) ---

            let debounceTimer;

            async function searchAddress(query, inputElement) {
                if (query.length < 3) {
                    clearResults(inputElement);
                    return;
                }
                const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&countrycodes=br&addressdetails=1`;

                try {
                    const response = await fetch(url);
                    const data = await response.json();
                    displayResults(data, inputElement);
                } catch (error) {
                    console.error('Erro ao buscar endereço:', error);
                }
            }

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
                // Adiciona o container de resultados APÓS o input, dentro do wrapper RELATIVE
                inputElement.closest('.relative').appendChild(resultsContainer); 
            }

            function clearResults(inputElement) {
                const parent = inputElement.closest('.relative');
                if (!parent) return;
                const resultsContainer = parent.querySelector('.autocomplete-results');
                if (resultsContainer) {
                    parent.removeChild(resultsContainer);
                }
            }

            function setupInputListener(inputElement) {
                inputElement.addEventListener('input', () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => {
                        searchAddress(inputElement.value, inputElement);
                    }, 300); 
                });
                document.addEventListener('click', (e) => {
                    // Verifica se o clique não foi dentro do container do input para fechar os resultados
                    const parentContainer = inputElement.closest('.relative');
                    if (parentContainer && !parentContainer.contains(e.target)) {
                        clearResults(inputElement);
                    }
                });
            }
            
            // Inicializa a escuta nos campos de endereço que já existem
            document.querySelectorAll('input[name="percursos[]"]').forEach(setupInputListener);

            // --- LÓGICA DE CARREGAMENTO DINÂMICO DE VEÍCULOS ---

            async function loadVehicles(motoboyId, selectedVehicleId = null) {
                // Se já carregamos para este motoboy, usamos o cache
                if (allVehicles[motoboyId]) {
                    renderVehicles(allVehicles[motoboyId], selectedVehicleId);
                    return;
                }

                veiculoSelect.innerHTML = '<option value="">Carregando...</option>';
                veiculoSelect.disabled = true;

                try {
                    const response = await fetch(`/api/motoboys/${motoboyId}/veiculos`);
                    const vehicles = await response.json();
                    
                    allVehicles[motoboyId] = vehicles;

                    renderVehicles(vehicles, selectedVehicleId);
                } catch (error) {
                    console.error('Erro ao buscar veículos:', error);
                    veiculoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                }
            }
            
            function renderVehicles(vehicles, selectedVehicleId) {
                veiculoSelect.innerHTML = '<option value="">Selecione um veículo</option>';
                if (vehicles.length > 0) {
                    veiculoSelect.disabled = false;
                    vehicles.forEach(veiculo => {
                        const option = document.createElement('option');
                        option.value = veiculo.id;
                        option.textContent = `${veiculo.modelo} - ${veiculo.placa}`;
                        // Mantém a seleção
                        if (veiculo.id == selectedVehicleId) {
                            option.selected = true;
                        }
                        veiculoSelect.appendChild(option);
                    });
                } else {
                    veiculoSelect.innerHTML = '<option value="">Nenhum veículo associado</option>';
                    veiculoSelect.disabled = true;
                }
            }


            // Listener principal para Motoboy
            motoboySelect.addEventListener('change', function () {
                const motoboyId = this.value;
                if (motoboyId) {
                    // Passa o ID do veículo atualmente selecionado/antigo para tentar mantê-lo
                    loadVehicles(motoboyId, veiculoSelect.value); 
                } else {
                    veiculoSelect.innerHTML = '<option value="">Selecione um motoboy primeiro</option>';
                    veiculoSelect.disabled = true;
                }
            });
            
            // Lógica de Repopulamento ao Carregar a Página (Para edição ou erro de validação)
            if (initialMotoboyId) {
                // Carrega os veículos iniciais (que vieram do controller) e mantém o valor selecionado
                loadVehicles(initialMotoboyId, initialVehicleId);
            }
            
            // Corrige a numeração inicial e os botões de remoção
            updateNumbers(); 
        });
    </script>
</x-app-layout>