<x-app-layout>
    <x-slot name="title">Masuk Grup</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Masuk Grup</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-5">Masukkan kode undangan 8 karakter yang diberikan oleh admin grup.</p>
                <form method="POST" action="{{ route('groups.join.post') }}">
                    @csrf
                    <div>
                        <x-input-label for="invite_code" value="Kode Undangan" />
                        <x-text-input id="invite_code" name="invite_code" type="text" class="mt-1 block w-full uppercase tracking-widest text-center text-xl" :value="old('invite_code')" maxlength="8" placeholder="XXXXXXXX" autofocus required />
                        <x-input-error :messages="$errors->get('invite_code')" class="mt-2" />
                    </div>
                    <div class="flex justify-end gap-3 mt-5">
                        <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <x-primary-button>Masuk</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
