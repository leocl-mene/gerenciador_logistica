<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Veículo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('veiculos.update', $veiculo->id) }}">
                        @csrf 
                        @method('PUT') 
                        
                        <div>
                            <label for="placa" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Placa</label>
                            <input id="placa" name="placa" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('placa', $veiculo->placa) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="modelo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Modelo</label>
                            <input id="modelo" name="modelo" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('modelo', $veiculo->modelo) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="marca" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Marca</label>
                            <input id="marca" name="marca" type="text" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('marca', $veiculo->marca) }}" />
                        </div>

                         <div class="mt-4">
                            <label for="ano" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Ano</label>
                            <input id="ano" name="ano" type="number" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('ano', $veiculo->ano) }}" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('veiculos.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <button type="submit" class="ms-4 bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                                Atualizar Veículo
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>