<x-app-layout>
    <x-slot name="title">Buat Grup</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('groups.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buat Grup Baru</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <form method="POST" action="{{ route('groups.store') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <x-input-label for="name" value="Nama Grup" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" placeholder="Contoh: Liburan Bali 2026" autofocus required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="type" value="Jenis Grup" />
                            <div class="mt-2 grid grid-cols-2 gap-3" id="type-selector">
                                @foreach(['patungan' => ['label' => 'Patungan', 'desc' => 'Bagi pengeluaran bersama', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'], 'iuran' => ['label' => 'Iuran', 'desc' => 'Tagihan atau iuran rutin', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4']] as $val => $info)
                                    <label class="relative flex flex-col gap-1 p-4 border-2 rounded-xl cursor-pointer transition-all
                                        {{ old('type', 'patungan') === $val ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                                        <input type="radio" name="type" value="{{ $val }}" class="sr-only" {{ old('type', 'patungan') === $val ? 'checked' : '' }}>
                                        <svg class="w-5 h-5 {{ old('type', 'patungan') === $val ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $info['icon'] }}"/>
                                        </svg>
                                        <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $info['label'] }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $info['desc'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" value="Deskripsi (opsional)" />
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-emerald-500 dark:focus:ring-emerald-500 rounded-lg text-sm"
                                placeholder="Jelaskan tujuan grup ini...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Batal
                            </a>
                            <x-primary-button>Buat Grup</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('#type-selector input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('#type-selector label').forEach(label => {
                    label.classList.remove('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                    label.classList.add('border-gray-200', 'dark:border-gray-700');
                    label.querySelector('svg').classList.remove('text-emerald-600', 'dark:text-emerald-400');
                    label.querySelector('svg').classList.add('text-gray-400');
                });
                const selected = radio.closest('label');
                selected.classList.add('border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                selected.classList.remove('border-gray-200', 'dark:border-gray-700');
                selected.querySelector('svg').classList.add('text-emerald-600', 'dark:text-emerald-400');
                selected.querySelector('svg').classList.remove('text-gray-400');
            });
        });
    </script>
</x-app-layout>
