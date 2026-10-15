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
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Perlu diterima</p>
                    </div>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($totalOwed, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari orang lain</p>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-red-50 dark:bg-red-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Perlu dibayar</p>
                    </div>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        Rp {{ number_format($totalDebt, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">ke orang lain</p>
                </div>

                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 bg-slate-100 dark:bg-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Grup aktif</p>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $groups->count() }}
                    </p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">grup yang kamu ikuti</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Transfer yang perlu dilakukan --}}
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Transfer yang Perlu Dilakukan</h3>
                    </div>
                    @if(empty($debtSummary))
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Tidak ada utang yang perlu diselesaikan!</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($debtSummary as $item)
                                <div class="flex items-center justify-between p-4">
                                    <div>
                                        @if($item['i_owe'])
                                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                                Kamu → <span class="font-semibold">{{ $item['to_member']?->display_name }}</span>
                                            </p>
                                        @else
                                            <p class="text-sm text-gray-900 dark:text-gray-100">
                                                <span class="font-semibold">{{ $item['from_member']?->display_name }}</span> → Kamu
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                            {{ $item['group']->name }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-semibold text-sm {{ $item['i_owe'] ? 'text-red-600 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                            {{ $item['i_owe'] ? '-' : '+' }}Rp {{ number_format($item['amount'], 0, ',', '.') }}
                                        </span>
                                        @if($item['i_owe'])
                                            <div class="mt-1">
                                                <a href="{{ route('groups.settlements.index', $item['group']) }}"
                                                   class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">
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
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Iuran Belum Dibayar</h3>
                    </div>
                    @if($unpaidDues->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Semua iuranmu sudah lunas!</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
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
                                               class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">
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
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pengeluaran Terbaru</h3>
                    </div>
                    <a href="{{ route('groups.index') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline">
                        Lihat semua grup →
                    </a>
                </div>
                @if($recentExpenses->isEmpty())
                    <div class="p-10 text-center">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengeluaran.</p>
                        <a href="{{ route('groups.create') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline mt-1 inline-block">Buat grup dulu</a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($recentExpenses as $item)
                            <a href="{{ route('groups.expenses.show', [$item['group'], $item['expense']]) }}"
                               class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition">
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
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Grup Kamu</h3>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('groups.join') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">Masuk Grup</a>
                            <a href="{{ route('groups.create') }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline font-medium">+ Buat Grup</a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($groups as $group)
                            <a href="{{ route('groups.show', $group) }}"
                               class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg {{ $group->type === 'patungan' ? 'bg-blue-50 dark:bg-blue-900/30' : 'bg-emerald-50 dark:bg-emerald-900/30' }} flex items-center justify-center flex-shrink-0">
                                        @if($group->type === 'patungan')
                                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $group->name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $group->members->count() }} anggota ·
                                            {{ $group->type === 'patungan' ? 'Patungan' : 'Iuran' }}
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
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Belum ada grup</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Buat grup baru atau masuk ke grup yang sudah ada.</p>
                    <div class="flex gap-3 justify-center mt-5">
                        <a href="{{ route('groups.join') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                            Masuk dengan Kode
                        </a>
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                            Buat Grup Baru
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
