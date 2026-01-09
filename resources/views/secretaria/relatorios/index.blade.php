<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatório de Utilização de Veículos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $activeTab = $tab ?? 'demandas';
            @endphp

            <div class="flex flex-wrap gap-2 mb-6">
                <a href="{{ route('relatorios.index') }}"
                   class="px-4 py-2 rounded font-semibold {{ $activeTab === 'demandas' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Demandas
                </a>
                <a href="{{ route('relatorios.abastecimentos') }}"
                   class="px-4 py-2 rounded font-semibold {{ $activeTab === 'abastecimentos' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                    Abastecimentos
                </a>
            </div>

            
            @if($activeTab === 'demandas')

            {{-- Formulário de Configuração do Preço da Gasolina --}}
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 mb-6">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <label for="preco_gasolina" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Preço Atual do Litro da Gasolina (R$)</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input 
                            type="number" 
                            step="0.01" 
                            name="preco_gasolina" 
                            id="preco_gasolina" 
                            value="{{ old('preco_gasolina', number_format((float)($preco_gasolina ?? 0), 2, '.', '')) }}" 
                            class="w-full md:w-1/4 rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                            required
                        >
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Salvar Preço</button>
                    </div>
                    @error('preco_gasolina')
                        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                    @enderror
                    @if (session('success'))
                        <p class="text-sm text-green-600 dark:text-green-400 mt-2">{{ session('success') }}</p>
                    @endif
                </form>
            </div>

            {{-- Formulário de Filtros do Relatório --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('relatorios.generate') }}" method="GET" id="report-form">
                    {{-- Filtros de Data --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label for="data_inicio" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data de Início</label>
                            <input type="date" name="data_inicio" id="data_inicio" value="{{ $data_inicio ?? '' }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="data_fim" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data Final</label>
                            <input type="date" name="data_fim" id="data_fim" value="{{ $data_fim ?? '' }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    
                    {{-- SELEÇÃO DE VEÍCULOS COM CHECKBOXES --}}
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Selecione um ou mais veículos</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 p-4 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 max-h-60 overflow-y-auto">
                            @foreach($veiculos as $veiculo)
                                @php
                                    // Adicionando um fallback para o consumo caso o accessor retorne algo inválido
                                    $consumo = method_exists($veiculo, 'getConsumoPadraoAttribute') ? $veiculo->consumo_padrao : 'N/A';
                                @endphp
                                <label class="flex items-center space-x-2 cursor-pointer p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    {{-- Nome do campo alterado para veiculos_ids[] --}}
                                    <input type="checkbox" name="veiculos_ids[]" value="{{ $veiculo->id }}" 
                                            class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                            {{-- Verifica se o ID do veículo está no array de IDs selecionados --}}
                                            @checked(in_array($veiculo->id, $veiculos_selecionados_ids ?? []))>
                                    <span class="text-sm text-gray-800 dark:text-gray-200 font-medium whitespace-nowrap">
                                        {{ $veiculo->placa }} 
                                        <span class="text-xs text-gray-500 dark:text-gray-400">({{ number_format((float)$consumo, 1, ',', '.') }} km/L)</span>
                                    </span>
                                </label>
                            @endforeach
                            @if ($errors->has('veiculos_ids'))
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2 col-span-full">{{ $errors->first('veiculos_ids') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Botões --}}
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

            {{-- Tabela de Pré-visualização --}}
            @if(isset($demandas))
            {{-- Adiciona a tabela de resultados somente se houver dados para pré-visualização --}}
            <div class="mt-6 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        Pré-visualização do Relatório
                        <span class="text-base font-normal text-gray-600 dark:text-gray-400 block sm:inline">
                             | Período de **{{ \Carbon\Carbon::parse($data_inicio)->format('d/m/Y') }}** a **{{ \Carbon\Carbon::parse($data_fim)->format('d/m/Y') }}**
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
                                @forelse ($demandas as $demanda)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ \Carbon\Carbon::parse($demanda->data_finalizacao)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $demanda->veiculo->placa ?? 'N/A' }}</td>
                                        
                                        {{-- CÓDIGO DO PERCURSO ATUALIZADO AQUI --}}
                                        <td class="px-6 py-4 text-sm">
                                            @if($demanda->tipo === 'urgente' && $demanda->gpsTracks->count() > 0)
                                                Rota Urgente (Gravada via GPS)
                                            @else
                                                {{ $demanda->percursos->pluck('endereco')->join(' &rarr; ') }}
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ number_format($demanda->km_inicial, 0, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">{{ number_format($demanda->km_final, 0, ',', '.') }}</td>
                                        {{-- Cálculo do KM Rodado --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center font-bold text-sm text-blue-600 dark:text-blue-400">
                                            {{ number_format($demanda->km_final - $demanda->km_inicial, 0, ',', '.') }}
                                        </td>
                                        {{-- Cálculo do Custo por Demanda --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 dark:text-red-400 font-semibold">
                                            @php
                                                $consumo = $demanda->veiculo->consumo_padrao ?? 0;
                                                $kmRodado = $demanda->km_final - $demanda->km_inicial;
                                                $custo = ($consumo > 1) ? ($kmRodado / $consumo) * (float)$preco_gasolina : 0;
                                            @endphp
                                            @if($custo > 0)
                                                R$ {{ number_format($custo, 2, ',', '.') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $demanda->motoboy->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Nenhuma demanda finalizada encontrada para os veículos selecionados no período.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            {{-- Linha de totalização --}}
                            <tfoot class="bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-right font-extrabold uppercase text-gray-900 dark:text-gray-100">Totais no Período:</td>
                                    {{-- Cálculo da soma total dos KM rodados --}}
                                    <td class="px-6 py-4 text-center font-extrabold text-lg text-green-600 dark:text-green-400">
                                        {{ number_format($demandas->sum(function($d) { return $d->km_final - $d->km_inicial; }), 0, ',', '.') }} km
                                    </td>
                                    {{-- Cálculo do Custo Total --}}
                                    <td class="px-6 py-4 text-center font-extrabold text-lg text-red-600 dark:text-red-400">
                                        R$ {{ number_format($demandas->sum(function($d) use ($preco_gasolina) {
                                            $consumo = $d->veiculo->consumo_padrao ?? 0;
                                            if ($consumo > 1) {
                                                return (($d->km_final - $d->km_inicial) / $consumo) * (float)$preco_gasolina;
                                            }
                                            return 0;
                                        }), 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            @endif

            @if($activeTab === 'abastecimentos')
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('relatorios.abastecimentos') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div>
                                <label for="data_inicio_abast" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data de Inicio</label>
                                <input type="date" name="data_inicio" id="data_inicio_abast" value="{{ $data_inicio ?? '' }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            <div>
                                <label for="data_fim_abast" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Data Final</label>
                                <input type="date" name="data_fim" id="data_fim_abast" value="{{ $data_fim ?? '' }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Selecione um ou mais veiculos (opcional)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 p-4 border dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 max-h-60 overflow-y-auto">
                                @foreach($veiculos as $veiculo)
                                    <label class="flex items-center space-x-2 cursor-pointer p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <input type="checkbox" name="veiculos_ids[]" value="{{ $veiculo->id }}"
                                                class="rounded dark:bg-gray-900 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                @checked(in_array($veiculo->id, $veiculos_selecionados_ids ?? []))>
                                        <span class="text-sm text-gray-800 dark:text-gray-200 font-medium whitespace-nowrap">
                                            {{ $veiculo->placa }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 mt-6">
                            <button type="submit" class="justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                Filtrar
                            </button>
                        </div>
                    </form>
                </div>

                @if(isset($abastecimentos))
                <div class="mt-6 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Abastecimentos no Periodo
                        </h3>
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Veiculo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Motorista</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor (R$)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cupom</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                                    @forelse ($abastecimentos as $abastecimento)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $abastecimento->data_abastecimento->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ $abastecimento->veiculo->placa ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $abastecimento->usuario->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">R$ {{ number_format($abastecimento->valor, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ $abastecimento->foto_url }}" target="_blank" class="text-blue-600 hover:text-blue-800">Ver foto</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">Nenhum abastecimento encontrado para o periodo.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if(isset($abastecimentos))
                                <tfoot class="bg-gray-100 dark:bg-gray-900 border-t border-gray-300 dark:border-gray-700">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right font-extrabold uppercase text-gray-900 dark:text-gray-100">Total no Periodo:</td>
                                        <td class="px-6 py-4 text-center font-extrabold text-lg text-green-600 dark:text-green-400">
                                            R$ {{ number_format($abastecimentos->sum('valor'), 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4"></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>