<x-app-layout>
    <x-slot name="title">Edit {{ $group->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Grup</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('groups.update', $group) }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <x-input-label for="name" value="Nama Grup" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $group->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi (opsional)" />
                            <textarea id="description" name="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $group->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex justify-between pt-2">
                            <form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('Hapus grup ini? Semua data akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">Hapus Grup</button>
                            </form>
                            <div class="flex gap-3">
                                <a href="{{ route('groups.show', $group) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                                    Batal
                                </a>
                                <x-primary-button>Simpan</x-primary-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
