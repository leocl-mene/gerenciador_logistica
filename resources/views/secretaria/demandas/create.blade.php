<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Atribuir Nova Demanda' }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

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

                    <form method="POST" action="{{ route('demandas.store') }}">
                        @csrf

                        {{-- INFORMAÇÕES BÁSICAS --}}
                        <div>
                            <label for="titulo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Título da Demanda</label>
                            <input id="titulo" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700" type="text" name="titulo" value="{{ old('titulo') }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="descricao" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Descrição (Opcional)</label>
                            <textarea id="descricao" name="descricao" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('descricao') }}</textarea>
                        </div>

                        <div class="block mt-4">
                            <label for="is_priority" class="inline-flex items-center">
                                <input id="is_priority" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" name="is_priority" value="1" @if(old('is_priority')) checked @endif>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Demanda Urgente</span>
                            </label>
                        </div>

                        <hr class="my-6 border-gray-600 dark:border-gray-700">

                        {{-- SELEÇÃO DE MOTOBOY E VEÍCULO (NOVO BLOCO) --}}
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Atribuição Imediata</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Seleção de Motoboy --}}
                            <div>
                                <label for="motoboy_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Atribuir para o Motoboy</label>
                                <select name="motoboy_id" id="motoboy_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Selecione um motoboy</option>
                                    @foreach($motoboys as $motoboy)
                                        <option value="{{ $motoboy->id }}" @selected(old('motoboy_id') == $motoboy->id)>{{ $motoboy->name }}</option>
                                    @endforeach
                                </select>
                                @error('motoboy_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            {{-- Seleção de Veículo (Dinâmico) --}}
                            <div>
                                <label for="veiculo_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Usando o Veículo</label>
                                <select name="veiculo_id" id="veiculo_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required disabled>
                                    <option value="">Selecione um motoboy primeiro</option>
                                </select>
                                @error('veiculo_id')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-6 border-gray-600 dark:border-gray-700">
                        {{-- FIM DO BLOCO DE ATRIBUIÇÃO --}}

                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Percurso da Demanda</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Pelo menos um ponto de partida e um de chegada são obrigatórios.</p>
                        <div id="percursos-container" class="mt-4 space-y-4">
                            {{-- Adicionada a classe "relative" para o autocompletar --}}
                            <div class="flex items-center gap-2 relative">
                                <span class="font-bold text-gray-800 dark:text-gray-200">1.</span>
                                <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Ponto de Partida (Ex: Endereço da Empresa)" value="{{ old('percursos.0') }}" required />
                            </div>
                            {{-- Adicionada a classe "relative" para o autocompletar --}}
                            <div class="flex items-center gap-2 relative">
                                <span class="font-bold text-gray-800 dark:text-gray-200">2.</span>
                                <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Ponto de Chegada/Destino" value="{{ old('percursos.1') }}" required />
                            </div>
                            
                            {{-- Campo para re-popular percursos antigos se houver erro de validação --}}
                            @php $oldPercursos = old('percursos'); @endphp
                            @if($oldPercursos && count($oldPercursos) > 2)
                                @for($i = 2; $i < count($oldPercursos); $i++)
                                    <div class="flex items-center gap-2 relative">
                                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $i + 1 }}.</span>
                                        <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Parada {{ $i }}" value="{{ $oldPercursos[$i] }}" required />
                                        <button type="button" class="remove-percurso text-red-500 hover:text-red-700 p-1 shrink-0">Remover</button>
                                    </div>
                                @endfor
                            @endif

                        </div>

                        <div class="mt-4">
                            <button type="button" id="add-percurso" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-white transition">+ Adicionar Parada Intermediária</button>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('demandas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">Cancelar</a>
                            <button type="submit" class="ms-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Atribuir Demanda</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT PARA DROPDOWNS DINÂMICOS E PERCURSOS --}}
    <script>
        // Objeto global para armazenar os veículos, caso o usuário troque de motoboy várias vezes
        let allVehicles = {}; 
        
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('percursos-container');
            const addButton = document.getElementById('add-percurso');
            let percursoCount = container.children.length;

            const motoboySelect = document.getElementById('motoboy_id');
            const veiculoSelect = document.getElementById('veiculo_id');
            const initialOldVehicleId = "{{ old('veiculo_id') }}";
            const initialOldMotoboyId = "{{ old('motoboy_id') }}";


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
                    if (!inputElement.closest('.relative').contains(e.target)) {
                        clearResults(inputElement);
                    }
                });
            }

            // --- LÓGICA DE GERENCIAMENTO DE PERCURSOS ---

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
                        input.placeholder = 'Ponto de Partida (Ex: Endereço da Empresa)';
                    }
                });
                percursoCount = currentCount;
            }

            // Inicializa a escuta nos campos de endereço que já existem
            document.querySelectorAll('input[name="percursos[]"]').forEach(setupInputListener);

            // Lógica para adicionar novos campos de percurso
            addButton.addEventListener('click', function () {
                const newIndex = container.children.length + 1;
                
                const newPercursoWrapper = document.createElement('div');
                newPercursoWrapper.classList.add('flex', 'items-center', 'gap-2', 'relative'); 

                const newPercursoHtml = `
                    <span class="font-bold text-gray-800 dark:text-gray-200">${newIndex}.</span>
                    <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block w-full" type="text" name="percursos[]" placeholder="Parada ${newIndex - 1}" required />
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
                        e.target.parentElement.remove();
                        updateNumbers(); // Renumera os itens restantes
                    } else {
                        alert('É necessário ter pelo menos um ponto de partida e um de destino.');
                    }
                }
            });

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
                    // Chama a API que criamos: /api/motoboys/{id}/veiculos
                    const response = await fetch(`/api/motoboys/${motoboyId}/veiculos`);
                    const vehicles = await response.json();
                    
                    // Armazena no cache
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
                    vehicles.forEach(veiculo => {
                        const option = document.createElement('option');
                        option.value = veiculo.id;
                        option.textContent = `${veiculo.modelo} - ${veiculo.placa}`;
                        // Mantém a seleção após erro de validação ou troca de motoboy
                        if (veiculo.id == selectedVehicleId) {
                            option.selected = true;
                        }
                        veiculoSelect.appendChild(option);
                    });
                    veiculoSelect.disabled = false;
                } else {
                    veiculoSelect.innerHTML = '<option value="">Nenhum veículo associado</option>';
                    // Não reabilita o select se não houver veículos
                }
            }


            // Listener principal para Motoboy
            motoboySelect.addEventListener('change', function () {
                const motoboyId = this.value;
                if (motoboyId) {
                    // Passa 'null' para carregar do zero, ou o ID antigo se houver
                    loadVehicles(motoboyId, initialOldVehicleId);
                } else {
                    veiculoSelect.innerHTML = '<option value="">Selecione um motoboy primeiro</option>';
                    veiculoSelect.disabled = true;
                }
            });
            
            // Lógica de Repopulamento ao Carregar a Página (após erro de validação)
            if (initialOldMotoboyId) {
                // Simula o evento de troca para carregar os veículos antigos
                loadVehicles(initialOldMotoboyId, initialOldVehicleId);
                // Define o valor do motoboy para reter o estado
                motoboySelect.value = initialOldMotoboyId; 
            }
            // Garante que a contagem de percursos está correta
            updateNumbers(); 
        });
    </script>
</x-app-layout>