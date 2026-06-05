<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-100">
            {{ __('Panel Główny') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-900 dark:shadow-gray-950">
                <div class="p-12 text-center text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4 sm:text-3xl lg:text-4xl">Witaj ponownie, {{ auth()->user()->name }}! 👋</h3>

                    <a href="{{ route('groups.index') }}" class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-black py-4 px-6 rounded-2xl shadow-2xl transition-all hover:scale-105 text-base sm:py-5 sm:px-10 sm:text-xl dark:bg-green-500 dark:hover:bg-green-400 dark:text-gray-950">
                        📂 ZARZĄDZAJ MOIMI GRUPAMI
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
