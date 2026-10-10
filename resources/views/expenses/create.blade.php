<x-app-layout>
    <x-slot name="title">Catat Pengeluaran — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Catat Pengeluaran</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form method="POST" action="{{ route('groups.expenses.store', $group) }}" id="expense-form">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <x-input-label for="description" value="Deskripsi" />
                            <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description')" placeholder="Contoh: Makan malam di warung" autofocus required />
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="amount" value="Total (Rp)" />
                                <x-text-input id="amount" name="amount" type="number" class="mt-1 block w-full" :value="old('amount')" min="1" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="spent_at" value="Tanggal" />
                                <x-text-input id="spent_at" name="spent_at" type="date" class="mt-1 block w-full" :value="old('spent_at', date('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('spent_at')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="paid_by_member_id" value="Dibayar oleh" />
                                <select id="paid_by_member_id" name="paid_by_member_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ old('paid_by_member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('paid_by_member_id')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="category" value="Kategori (opsional)" />
                                <x-text-input id="category" name="category" type="text" class="mt-1 block w-full" :value="old('category')" placeholder="Makan, Transport, dll" />
                                <x-input-error :messages="$errors->get('category')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Split Type --}}
                        <div>
                            <x-input-label value="Cara Bagi" />
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach(['equal' => 'Sama Rata', 'percentage' => 'Persentase', 'exact' => 'Nominal', 'by_item' => 'Per Item'] as $val => $label)
                                    <label class="flex items-center justify-center gap-2 px-3 py-2 border rounded-lg cursor-pointer text-sm font-medium transition
                                        {{ old('split_type', 'equal') === $val ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50' }}">
                                        <input type="radio" name="split_type" value="{{ $val }}" class="sr-only" {{ old('split_type', 'equal') === $val ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('split_type')" class="mt-2" />
                        </div>

                        {{-- Pilih anggota --}}
                        <div>
                            <x-input-label value="Anggota yang ikut" />
                            <div class="mt-2 space-y-2" id="member-list">
                                @foreach($members as $member)
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <input type="checkbox" name="member_ids[]" value="{{ $member->id }}" checked class="rounded border-gray-300 text-indigo-600">
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->display_name }}</span>
                                        <div class="ml-auto hidden exact-input">
                                            <input type="number" name="exact_amounts[]" placeholder="0" min="0" class="w-28 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md" />
                                        </div>
                                        <div class="ml-auto hidden percentage-input">
                                            <input type="number" name="percentages[]" placeholder="0" min="0" max="100" step="0.01" class="w-20 text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 rounded-md" />
                                            <span class="text-sm text-gray-500 ml-1">%</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('member_ids')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <a href="{{ route('groups.show', $group) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <x-primary-button>Simpan</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle split type styling & inputs
        document.querySelectorAll('input[name="split_type"]').forEach(radio => {
            radio.addEventListener('change', () => {
                document.querySelectorAll('input[name="split_type"]').forEach(r => {
                    r.closest('label').className = r.closest('label').className
                        .replace('bg-indigo-600 text-white border-indigo-600', 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50');
                });
                radio.closest('label').className = radio.closest('label').className
                    .replace('bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50', 'bg-indigo-600 text-white border-indigo-600');

                const isExact = radio.value === 'exact';
                const isPct = radio.value === 'percentage';
                document.querySelectorAll('.exact-input').forEach(el => el.classList.toggle('hidden', !isExact));
                document.querySelectorAll('.percentage-input').forEach(el => el.classList.toggle('hidden', !isPct));
            });
        });
    </script>
</x-app-layout>
