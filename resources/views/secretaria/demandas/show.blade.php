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
                <p><strong>Criada por:</strong> {{ $demanda->secretaria->name }} em {{ $demanda->created_at->format('d/m/Y H:i') }}</p>
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
                    @foreach($demanda->percursos as $percurso)
                        <li>{{ $percurso->endereco }}</li>
                    @endforeach
                </ol>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 h-fit">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Fotos do Odômetro</h3>
                @if($demanda->fotosKm)
                    <div>
                        <p class="font-semibold">KM Inicial:</p>
                        <img src="{{ $demanda->fotosKm->foto_url_inicio }}" alt="Foto KM Inicial" class="mt-2 rounded-lg w-full">
                    </div>
                    @if($demanda->fotosKm->foto_url_final)
                        <div class="mt-4">
                            <p class="font-semibold">KM Final:</p>
                            <img src="{{ $demanda->fotosKm->foto_url_final }}" alt="Foto KM Final" class="mt-2 rounded-lg w-full">
                        </div>
                    @else
                        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Aguardando foto do KM final.</p>
                    @endif
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma foto de KM enviada para esta demanda.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>