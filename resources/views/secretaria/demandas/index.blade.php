<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gerenciamento de Demandas') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
             @if (session('error'))
                <div class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('demandas.create') }}" class="bg-primaria hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            Lançar Nova Demanda
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th> {{-- NOVA COLUNA --}}
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Prioridade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($demandas as $demanda)
                                    <tr>
                                        {{-- CÓDIGO DO TÍTULO --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('demandas.show', $demanda->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 font-semibold">
                                                {{ $demanda->titulo }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $demanda->status }}</td>

                                        {{-- NOVA COLUNA: TIPO (Normal vs Urgente App) --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($demanda->tipo == 'urgente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-200 text-purple-800 dark:bg-purple-700 dark:text-purple-100">
                                                    Urgente (App)
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-200 text-blue-800 dark:bg-blue-700 dark:text-blue-100">
                                                    Normal
                                                </span>
                                            @endif
                                        </td>
                                        
                                        {{-- COLUNA PRIORIDADE (Ajustada para usar is_priority, ignorando 'tipo') --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($demanda->is_priority && $demanda->tipo != 'urgente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-200 text-red-800 dark:bg-red-700 dark:text-red-100">Prioridade</span>
                                            @else
                                                {{-- Se não for prioridade ou for urgente (que já é tipo 'urgente'), fica vazio/normal --}}
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Padrão</span>
                                            @endif
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $demanda->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-4 justify-end">
                                            <a href="{{ route('demandas.show', $demanda->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400">Ver Detalhes</a>
                                            <a href="{{ route('demandas.edit', $demanda->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Editar</a>
                                            <form action="{{ route('demandas.destroy', $demanda->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta demanda?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400">Excluir</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-6 py-4 text-center dark:text-gray-400">Nenhuma demanda lançada.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>