<x-app-layout>
    <x-slot name="title">Buat Iuran — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Buat Iuran Baru</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('groups.dues.store', $group) }}">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <x-input-label for="title" value="Nama Iuran" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" placeholder="Contoh: Iuran Kas Oktober 2026" autofocus required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="amount" value="Nominal (Rp)" />
                                <x-text-input id="amount" name="amount" type="number" class="mt-1 block w-full" :value="old('amount')" min="1" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="due_date" value="Jatuh Tempo" />
                                <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date')" required />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="recurrence" value="Pengulangan" />
                            <select id="recurrence" name="recurrence" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="once" {{ old('recurrence', 'once') === 'once' ? 'selected' : '' }}>Sekali saja</option>
                                <option value="weekly" {{ old('recurrence') === 'weekly' ? 'selected' : '' }}>Setiap minggu</option>
                                <option value="monthly" {{ old('recurrence') === 'monthly' ? 'selected' : '' }}>Setiap bulan</option>
                                <option value="yearly" {{ old('recurrence') === 'yearly' ? 'selected' : '' }}>Setiap tahun</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Anggota yang wajib bayar" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-2">Kosongkan untuk pilih semua anggota.</p>
                            <div class="space-y-2">
                                @foreach($group->members as $member)
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" checked class="rounded border-gray-300 text-indigo-600">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->display_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('groups.dues.index', $group) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <x-primary-button>Buat Iuran</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
