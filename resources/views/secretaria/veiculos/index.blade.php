<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Veículos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- BLOCO DE MENSAGEM DE SUCESSO --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ route('veiculos.create') }}" class="bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                            Adicionar Novo Veículo
                        </a>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Placa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Modelo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Marca</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($veiculos as $veiculo)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $veiculo->placa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $veiculo->modelo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $veiculo->marca }}</td>
                                    
                                    {{-- CÉLULA DE AÇÕES ATUALIZADA COM O BOTÃO EXCLUIR --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-4 justify-end">
                                        <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                                    
                                        <form action="{{ route('veiculos.destroy', $veiculo->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este veículo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center">Nenhum veículo cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

