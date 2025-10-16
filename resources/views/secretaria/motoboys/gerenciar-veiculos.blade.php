<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Associar Veículos para: <span class="text-primaria">{{ $motoboy->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('motoboys.veiculos.salvar', $motoboy->id) }}">
                        @csrf
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                            Selecione os veículos que este motoboy pode utilizar:
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse ($todosVeiculos as $veiculo)
                                <label class="flex items-center p-3 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="checkbox" name="veiculos_ids[]" value="{{ $veiculo->id }}"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                           @if(in_array($veiculo->id, $veiculosAtuais)) checked @endif>
                                    <span class="ml-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $veiculo->modelo }} - {{ $veiculo->placa }}
                                    </span>
                                </label>
                            @empty
                                <p>Nenhum veículo cadastrado. Cadastre um veículo primeiro.</p>
                            @endforelse
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('motoboys.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md">
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
</x-app-layout>