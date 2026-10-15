<x-app-layout>
    <x-slot name="title">Daftar Iuran — {{ $group->name }}</x-slot>
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
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Daftar Iuran</h2>
                </div>
            </div>
            <a href="{{ route('groups.dues.create', $group) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Iuran
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($dues->isEmpty())
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Belum ada iuran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Buat iuran pertama untuk grup ini.</p>
                    <a href="{{ route('groups.dues.create', $group) }}"
                       class="inline-flex items-center gap-1.5 mt-5 px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat Iuran Pertama
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($dues as $due)
                        @php
                            $total = $due->payments->count();
                            $paid = $due->payments->where('status', 'paid')->count();
                            $allPaid = $paid === $total && $total > 0;
                        @endphp
                        <a href="{{ route('groups.dues.show', [$group, $due]) }}"
                           class="block bg-white dark:bg-gray-900 rounded-xl border {{ $allPaid ? 'border-emerald-200 dark:border-emerald-800' : 'border-gray-200 dark:border-gray-800' }} hover:border-emerald-300 dark:hover:border-emerald-700 hover:shadow-sm transition-all duration-150">
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $due->title }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                            Rp {{ number_format($due->amount, 0, ',', '.') }} · Jatuh tempo {{ $due->due_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="text-sm font-semibold tabular-nums {{ $allPaid ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $paid }}/{{ $total }} lunas
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all {{ $allPaid ? 'bg-emerald-500' : 'bg-amber-400' }}"
                                         style="width: {{ $total > 0 ? ($paid/$total*100) : 0 }}%"></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
