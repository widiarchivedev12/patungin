<x-app-layout>
    <x-slot name="title">{{ $group->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('groups.index') }}" class="hover:text-gray-700 dark:hover:text-gray-200 transition">Grup</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $group->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-xl text-gray-900 dark:text-gray-100">{{ $group->name }}</h2>
                    <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $group->type === 'patungan' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                        {{ $group->type === 'patungan' ? 'Patungan' : 'Iuran' }}
                    </span>
                </div>
            </div>
            @if($currentMember?->isAdmin())
                <div class="flex gap-2">
                    <a href="{{ route('groups.invite', $group) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Undang
                    </a>
                    <a href="{{ route('groups.edit', $group) }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Edit
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('groups.expenses.create', $group) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Catat Pengeluaran
                </a>
                @if($group->type === 'iuran')
                    <a href="{{ route('groups.dues.index', $group) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Daftar Iuran
                    </a>
                    @if($currentMember?->isAdmin())
                        <a href="{{ route('groups.dues.create', $group) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat Iuran
                        </a>
                    @endif
                @endif
                <a href="{{ route('groups.settlements.index', $group) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    Catat Pelunasan
                </a>
            </div>

            {{-- Ringkasan Saldo --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Siapa Transfer ke Siapa</h3>
                    </div>
                    <a href="{{ route('groups.settlements.index', $group) }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">Lihat Pelunasan</a>
                </div>
                @if(empty($simplifiedDebts))
                    <div class="flex items-center gap-2 text-sm text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg px-4 py-3">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Semua sudah lunas!
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($simplifiedDebts as $debt)
                            @php
                                $from = $group->members->find($debt['from']);
                                $to = $group->members->find($debt['to']);
                            @endphp
                            <div class="flex items-center justify-between py-2.5 px-3 bg-slate-50 dark:bg-gray-800/50 rounded-lg">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $from?->display_name }}</span>
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $to?->display_name }}</span>
                                </div>
                                <span class="font-bold text-red-600 dark:text-red-400 text-sm">Rp {{ number_format($debt['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                {{-- Pengeluaran Terbaru --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pengeluaran</h3>
                    </div>
                    @if($group->expenses->isEmpty())
                        <div class="p-10 text-center">
                            <div class="w-12 h-12 bg-slate-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada pengeluaran yang dicatat.</p>
                            <a href="{{ route('groups.expenses.create', $group) }}" class="text-sm text-emerald-600 dark:text-emerald-400 hover:underline mt-1 inline-block">Catat sekarang →</a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach($group->expenses as $expense)
                                <a href="{{ route('groups.expenses.show', [$group, $expense]) }}"
                                   class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-gray-800/50 transition">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $expense->description }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $expense->paidBy?->display_name }} · {{ $expense->spent_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100 text-sm">Rp {{ number_format($expense->amount, 0, ',', '.') }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Anggota & Saldo --}}
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">Anggota</h3>
                        </div>
                        @if($currentMember?->isAdmin())
                            <a href="{{ route('groups.invite', $group) }}" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline">+ Undang</a>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($group->members as $member)
                            @php $balance = $balances[$member->id] ?? 0; @endphp
                            <div class="flex items-center justify-between p-4">
                                <div>
                                    <div class="flex items-center gap-1.5">
                                        <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $member->display_name }}</p>
                                        @if($member->is_guest)
                                            <span class="text-xs text-gray-400 bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded">Tamu</span>
                                        @elseif($member->isAdmin())
                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded">Admin</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-sm font-semibold tabular-nums {{ $balance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $balance >= 0 ? '+' : '' }}Rp {{ number_format($balance, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
