<x-app-layout>
    <x-slot name="title">{{ $due->title }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('groups.dues.index', $group) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mb-0.5">
                        <a href="{{ route('groups.show', $group) }}" class="hover:underline">{{ $group->name }}</a>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span>Iuran</span>
                    </div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $due->title }}</h2>
                </div>
            </div>
            @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                <form method="POST" action="{{ route('groups.dues.destroy', [$group, $due]) }}" onsubmit="return confirm('Hapus iuran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Ringkasan --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Nominal</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100 tabular-nums">Rp {{ number_format($due->amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Jatuh Tempo</p>
                    <p class="font-bold text-base text-gray-900 dark:text-gray-100">{{ $due->due_date->format('d M Y') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Sudah Bayar</p>
                    <p class="font-bold text-lg text-emerald-600 dark:text-emerald-400 tabular-nums">{{ $paidCount }}/{{ $totalCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Pengulangan</p>
                    <p class="font-bold text-base text-gray-900 dark:text-gray-100 capitalize">
                        {{ match($due->recurrence) { 'once' => 'Sekali', 'weekly' => 'Mingguan', 'monthly' => 'Bulanan', 'yearly' => 'Tahunan', default => $due->recurrence } }}
                    </p>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-5">
                <div class="flex justify-between text-sm mb-2.5">
                    <span class="text-gray-600 dark:text-gray-400">Progress Pembayaran</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100 tabular-nums">{{ $totalCount > 0 ? round($paidCount/$totalCount*100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                         style="width: {{ $totalCount > 0 ? ($paidCount/$totalCount*100) : 0 }}%"></div>
                </div>
            </div>

            {{-- Daftar pembayaran --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Status Pembayaran</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($due->payments as $payment)
                        <div class="flex items-center justify-between p-4 gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $payment->member->display_name }}</p>
                                @if($payment->paid_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Dibayar {{ $payment->paid_at->format('d M Y') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($payment->status === 'paid')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Lunas
                                    </span>

                                @elseif($payment->status === 'pending_verification')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Menunggu Konfirmasi
                                    </span>
                                    @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                                        <form method="POST" action="{{ route('groups.dues.payments.confirm', [$group, $payment]) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @endif

                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Belum Bayar
                                    </span>
                                    @if($payment->member->user_id === auth()->id())
                                        <form method="POST" action="{{ route('groups.dues.payments.proof', [$group, $payment]) }}"
                                              enctype="multipart/form-data" id="proof-form-{{ $payment->id }}">
                                            @csrf
                                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg text-xs font-medium text-emerald-700 dark:text-emerald-300 cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                                </svg>
                                                <span id="file-label-{{ $payment->id }}">Upload Bukti</span>
                                                <input type="file" name="proof" accept="image/*,.pdf" class="sr-only" required
                                                       onchange="document.getElementById('file-label-{{ $payment->id }}').textContent = this.files[0]?.name?.substring(0, 20) || 'Upload Bukti'; var b = document.getElementById('submit-proof-{{ $payment->id }}'); b.classList.remove('hidden'); b.classList.add('inline-flex');">
                                            </label>
                                            <button id="submit-proof-{{ $payment->id }}" type="submit"
                                                    class="hidden items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-lg hover:bg-emerald-700 transition">
                                                Kirim
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
