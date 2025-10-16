<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Motoboy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('motoboys.update', $motoboy->id) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="name" :value="__('Nome')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $motoboy->name)" required autofocus />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $motoboy->email)" required />
                        </div>
                         <div class="mt-4">
                            <x-input-label for="telefone" :value="__('Telefone')" />
                            <x-text-input id="telefone" class="block mt-1 w-full" type="text" name="telefone" :value="old('telefone', $motoboy->telefone)" />
                        </div>
                        <hr class="my-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Deixe os campos de senha em branco para não alterá-la.</p>
                         <div class="mt-4">
                            <x-input-label for="password" :value="__('Nova Senha')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                        </div>
                         <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirmar Nova Senha')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('motoboys.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-md">Cancelar</a>
                            <x-primary-button class="ms-4">Atualizar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>