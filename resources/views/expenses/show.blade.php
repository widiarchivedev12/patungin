<x-app-layout>
    <x-slot name="title">{{ $expense->description }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $expense->description }}</h2>
            <div class="flex gap-2">
                <a href="{{ route('groups.expenses.edit', [$group, $expense]) }}" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('groups.expenses.destroy', [$group, $expense]) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition">Hapus</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Total</dt>
                        <dd class="mt-1 font-bold text-xl text-gray-900 dark:text-gray-100">Rp {{ number_format($expense->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Dibayar oleh</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ $expense->paidBy?->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Tanggal</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $expense->spent_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Cara Bagi</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ ucfirst($expense->split_type) }}</dd>
                    </div>
                    @if($expense->category)
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Kategori</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $expense->category }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pembagian</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($expense->shares as $share)
                        <div class="flex items-center justify-between p-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $share->member?->display_name }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($share->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('groups.show', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Kembali ke grup</a>
        </div>
    </div>
</x-app-layout>
