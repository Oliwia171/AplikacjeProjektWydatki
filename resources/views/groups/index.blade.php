<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 dark:bg-gray-900 dark:shadow-gray-950">

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xl font-bold text-gray-800 sm:text-2xl dark:text-gray-100">📂 Moje Grupy Rozliczeniowe</h2>
                </div>

                <form action="{{ route('groups.store') }}" method="POST" class="bg-gray-50 p-4 rounded-xl mb-8 border border-gray-200 flex flex-col gap-4 sm:flex-row dark:bg-gray-950 dark:border-gray-800">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="name" placeholder="Nazwa nowej wycieczki/grupy..." class="w-full border-gray-300 bg-white text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" required>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-md transition">
                        + Dodaj nową
                    </button>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-950">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Nazwa Grupy</th>
                                @if(auth()->user()->isAdmin())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Wlasciciel</th>
                                @endif
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">Akcje</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-800">
                            @forelse($groups as $group)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">
                                        {{ $group->name }}
                                    </td>
                                    @if(auth()->user()->isAdmin())
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                            {{ $group->owner->name ?? '-' }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('groups.show', $group) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md dark:bg-indigo-950 dark:text-indigo-300 dark:hover:text-indigo-100">Otwórz &rarr;</a>

                                        <form action="{{ route('groups.destroy', $group) }}" method="POST" class="inline-block" onsubmit="return confirm('Czy na pewno chcesz usunąć tę grupę?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md dark:bg-red-950 dark:text-red-300 dark:hover:text-red-100">Usuń</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-10 text-center text-gray-500 italic dark:text-gray-400">
                                        Brak aktywnych grup. Stwórz pierwszą powyżej!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
