<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Halo, {{ auth()->user()->name }}! 👋
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Kartu ringkasan utang/piutang --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kamu perlu terima</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">
                        Rp {{ number_format($totalOwed, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari orang lain</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kamu perlu bayar</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">
                        Rp {{ number_format($totalDebt, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">ke orang lain</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Grup aktif</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                        {{ $groups->count() }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">grup yang kamu ikuti</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Transfer yang perlu dilakukan --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Transfer yang Perlu Dilakukan</h3>
                    </div>
                    @if(empty($debtSummary))
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            🎉 Tidak ada utang yang perlu diselesaikan!
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($debtSummary as $item)
                                <div class="flex items-center justify-between p-4">
                                    <div>
                                        @if($item['i_owe'])
                                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                                Kamu → <span class="font-medium">{{ $item['to_member']?->display_name }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                                <span class="font-medium">{{ $item['from_member']?->display_name }}</span> → Kamu
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $item['group']->name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-sm {{ $item['i_owe'] ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                            {{ $item['i_owe'] ? '-' : '+' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                        </span>
                                        @if($item['i_owe'])
                                            <div class="mt-1">
                                                <a href="{{ route('groups.settlements.index', $item['group']) }}"
                                                   class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    Catat pelunasan →
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Iuran belum dibayar --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Iuran Belum Dibayar</h3>
                    </div>
                    @if($unpaidDues->isEmpty())
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            ✅ Semua iuranmu sudah lunas!
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($unpaidDues as $payment)
                                <div class="flex items-center justify-between p-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $payment->due->title }}
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $payment->due->group->name }} ·
                                            Jatuh tempo {{ $payment->due->due_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-sm text-red-600 dark:text-red-400">
                                            Rp {{ number_format($payment->due->amount, 0, ',', '.') }}
                                        </span>
                                        <div class="mt-1">
                                            <a href="{{ route('groups.dues.show', [$payment->due->group, $payment->due]) }}"
                                               class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                                                Upload bukti →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- Pengeluaran terbaru --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pengeluaran Terbaru</h3>
                    <a href="{{ route('groups.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat semua grup →
                    </a>
                </div>
                @if($recentExpenses->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                        Belum ada pengeluaran.
                        <a href="{{ route('groups.create') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Buat grup dulu</a>.
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($recentExpenses as $item)
                            <a href="{{ route('groups.expenses.show', [$item['group'], $item['expense']]) }}"
                               class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $item['expense']->description }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                        {{ $item['group']->name }} ·
                                        {{ $item['expense']->paidBy?->display_name }} ·
                                        {{ $item['expense']->spent_at->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="font-semibold text-sm text-gray-900 dark:text-gray-100">
                                    Rp {{ number_format($item['expense']->amount, 0, ',', '.') }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Daftar grup --}}
            @if($groups->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Grup Kamu</h3>
                        <div class="flex gap-3">
                            <a href="{{ route('groups.join') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:underline">Masuk Grup</a>
                            <a href="{{ route('groups.create') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">+ Buat Grup</a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($groups as $group)
                            <a href="{{ route('groups.show', $group) }}"
                               class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex w-9 h-9 rounded-full {{ $group->type === 'patungan' ? 'bg-blue-100 dark:bg-blue-900/50' : 'bg-emerald-100 dark:bg-emerald-900/50' }} items-center justify-center text-base flex-shrink-0">
                                        {{ $group->type === 'patungan' ? '💰' : '📋' }}
                                    </span>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $group->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $group->members->count() }} anggota ·
                                            {{ ucfirst($group->type) }}
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="text-5xl mb-4">💰</div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100">Belum ada grup</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Buat grup baru atau masuk ke grup yang sudah ada.</p>
                    <div class="flex gap-3 justify-center mt-5">
                        <a href="{{ route('groups.join') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                            Masuk dengan Kode
                        </a>
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                            Buat Grup Baru
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
