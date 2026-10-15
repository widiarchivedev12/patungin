<x-app-layout>
    <x-slot name="title">{{ $expense->description }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('groups.show', $group) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $group->name }}</p>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $expense->description }}</h2>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('groups.expenses.edit', [$group, $expense]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('groups.expenses.destroy', [$group, $expense]) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <div class="text-center mb-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Pengeluaran</p>
                    <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 tabular-nums">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                </div>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Dibayar oleh</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-gray-100">{{ $expense->paidBy?->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Tanggal</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $expense->spent_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Cara Bagi</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-100 capitalize">{{ $expense->split_type }}</dd>
                    </div>
                    @if($expense->category)
                        <div>
                            <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kategori</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-100">{{ $expense->category }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pembagian</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($expense->shares as $share)
                        <div class="flex items-center justify-between p-4">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $share->member?->display_name }}</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 tabular-nums">Rp {{ number_format($share->amount, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
