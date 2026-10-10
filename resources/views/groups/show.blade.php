<x-app-layout>
    <x-slot name="title">{{ $group->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('groups.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-sm">Grup</a>
                    <span class="text-gray-400">/</span>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">{{ $group->name }}</h2>
                </div>
                <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full mt-1 {{ $group->type === 'patungan' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ $group->type === 'patungan' ? 'Patungan' : 'Iuran' }}
                </span>
            </div>
            @if($currentMember?->isAdmin())
                <div class="flex gap-2">
                    <a href="{{ route('groups.invite', $group) }}" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                        Undang Anggota
                    </a>
                    <a href="{{ route('groups.edit', $group) }}" class="inline-flex items-center px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                        Edit
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan Saldo --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Siapa Transfer ke Siapa</h3>
                    <a href="{{ route('groups.settlements.index', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Pelunasan</a>
                </div>
                @if(empty($simplifiedDebts))
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Semua sudah lunas! 🎉</p>
                @else
                    <div class="space-y-2">
                        @foreach($simplifiedDebts as $debt)
                            @php
                                $from = $group->members->find($debt['from']);
                                $to = $group->members->find($debt['to']);
                            @endphp
                            <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $from?->display_name }}</span>
                                    <span class="text-gray-400">→</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $to?->display_name }}</span>
                                </div>
                                <span class="font-semibold text-red-600 dark:text-red-400">Rp {{ number_format($debt['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('groups.expenses.create', $group) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                    + Catat Pengeluaran
                </a>
                @if($group->type === 'iuran')
                    <a href="{{ route('groups.dues.index', $group) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                        Daftar Iuran
                    </a>
                    @if($currentMember?->isAdmin())
                        <a href="{{ route('groups.dues.create', $group) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                            + Buat Iuran
                        </a>
                    @endif
                @endif
                <a href="{{ route('groups.settlements.index', $group) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                    Catat Pelunasan
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Pengeluaran Terbaru --}}
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Pengeluaran Terbaru</h3>
                    </div>
                    @if($group->expenses->isEmpty())
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            Belum ada pengeluaran yang dicatat.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($group->expenses as $expense)
                                <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
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
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Anggota</h3>
                        @if($currentMember?->isAdmin())
                            <a href="{{ route('groups.invite', $group) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">+ Undang</a>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($group->members as $member)
                            @php $balance = $balances[$member->id] ?? 0; @endphp
                            <div class="flex items-center justify-between p-4">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $member->display_name }}</p>
                                    @if($member->is_guest)
                                        <span class="text-xs text-gray-400">Tamu</span>
                                    @elseif($member->isAdmin())
                                        <span class="text-xs text-indigo-500">Admin</span>
                                    @endif
                                </div>
                                <span class="text-sm font-semibold {{ $balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
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
