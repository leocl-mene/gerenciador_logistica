<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalhes da Demanda: {{ $demanda->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informações Gerais</h3>
                
                <p><strong>Status:</strong> {{ $demanda->status }}</p>
                <p><strong>Tipo:</strong> {{ $demanda->tipo ?? 'Normal' }}</p>

                {{-- Lógica para exibir quem criou a demanda --}}
                @if ($demanda->secretaria)
                    <p><strong>Criada por:</strong> {{ $demanda->secretaria->name }} em {{ $demanda->created_at->format('d/m/Y H:i') }}</p>
                @elseif ($demanda->tipo === 'urgente' && $demanda->motoboy)
                    <p><strong>Criada por (Urgente):</strong> {{ $demanda->motoboy->name }} em {{ $demanda->created_at->format('d/m/Y H:i') }}</p>
                @endif
                
                @if($demanda->veiculo)
                    <p><strong>Veículo Utilizado:</strong> {{ $demanda->veiculo->modelo }} ({{ $demanda->veiculo->placa }})</p>
                @endif
                
                <hr class="my-3 border-gray-700">

                @if($demanda->motoboy)
                    <p><strong>Motoboy:</strong> {{ $demanda->motoboy->name }}</p>
                    <p><strong>Aceita em:</strong> {{ $demanda->data_aceite ? \Carbon\Carbon::parse($demanda->data_aceite)->format('d/m/Y H:i') : 'N/A' }}</p>

                    {{-- INFORMAÇÕES DE QUILOMETRAGEM ADICIONADAS AQUI --}}
                    @if($demanda->km_inicial)
                        <p class="mt-3"><strong>KM Inicial Digitado:</strong> {{ number_format($demanda->km_inicial, 0, ',', '.') }} km</p>
                    @endif
                    @if($demanda->km_final)
                        <p><strong>KM Final Digitado:</strong> {{ number_format($demanda->km_final, 0, ',', '.') }} km</p>
                        <p class="font-bold text-lg text-indigo-500 dark:text-indigo-400">
                            <strong>KM Rodado (Calculado):</strong> {{ number_format($demanda->km_final - $demanda->km_inicial, 0, ',', '.') }} km
                        </p>
                    @endif
                    {{-- FIM DA INFORMAÇÃO DE QUILOMETRAGEM --}}
                @endif
                
                @if($demanda->descricao)
                    <p class="mt-4"><strong>Descrição:</strong><br>{{ $demanda->descricao }}</p>
                @endif

                <hr class="my-6 border-gray-600">

                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Percurso Completo</h3>
                <ol class="list-decimal list-inside space-y-2">
                    @forelse($demanda->percursos as $percurso)
                        <li>{{ $percurso->endereco }}</li>
                    @empty
                        <p class="text-gray-500">Nenhum percurso definido para esta demanda.</p>
                    @endforelse
                </ol>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Fotos do Odômetro</h3>
                @if($demanda->fotosKm)
                    <div>
                        <p class="font-semibold">KM Inicial:</p>
                        @if($demanda->fotosKm->foto_url_inicio)
                            <a href="{{ $demanda->fotosKm->foto_url_inicio }}" target="_blank">
                                <img src="{{ $demanda->fotosKm->foto_url_inicio }}" alt="Foto KM Inicial" class="mt-2 rounded-lg w-full">
                            </a>
                        @else
                           <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Sem foto de KM inicial.</p>
                        @endif
                    </div>
                    @if($demanda->fotosKm->foto_url_final)
                        <div class="mt-4">
                            <p class="font-semibold">KM Final:</p>
                            <a href="{{ $demanda->fotosKm->foto_url_final }}" target="_blank">
                                <img src="{{ $demanda->fotosKm->foto_url_final }}" alt="Foto KM Final" class="mt-2 rounded-lg w-full">
                            </a>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Aguardando foto do KM final.</p>
                    @endif
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma foto de KM enviada para esta demanda.</p>
                @endif
            </div>

        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
             <a href="{{ route('demandas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                Voltar para a Lista
            </a>
        </div>
    </div>
</x-app-layout>

