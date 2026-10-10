<x-app-layout>
    <x-slot name="title">{{ $due->title }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                    <a href="{{ route('groups.show', $group) }}" class="hover:underline">{{ $group->name }}</a>
                    <span>/</span>
                    <a href="{{ route('groups.dues.index', $group) }}" class="hover:underline">Iuran</a>
                </div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $due->title }}</h2>
            </div>
            @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                <form method="POST" action="{{ route('groups.dues.destroy', [$group, $due]) }}" onsubmit="return confirm('Hapus iuran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 transition">Hapus</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Nominal</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100 mt-1">Rp {{ number_format($due->amount, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jatuh Tempo</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100 mt-1">{{ $due->due_date->format('d M Y') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sudah Bayar</p>
                    <p class="font-bold text-lg text-green-600 dark:text-green-400 mt-1">{{ $paidCount }}/{{ $totalCount }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pengulangan</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-gray-100 mt-1 capitalize">{{ $due->recurrence === 'once' ? 'Sekali' : ucfirst($due->recurrence) }}</p>
                </div>
            </div>

            {{-- Progress bar --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Terkumpul</span>
                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $totalCount > 0 ? round($paidCount/$totalCount*100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full transition-all" style="width: {{ $totalCount > 0 ? ($paidCount/$totalCount*100) : 0 }}%"></div>
                </div>
            </div>

            {{-- Daftar pembayaran --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">Status Pembayaran</h3>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($due->payments as $payment)
                        <div class="flex items-center justify-between p-4">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $payment->member->display_name }}</p>
                                @if($payment->paid_at)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Dibayar {{ $payment->paid_at->format('d M Y') }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3">
                                @if($payment->status === 'paid')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Lunas</span>
                                @elseif($payment->status === 'pending_verification')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Menunggu Konfirmasi</span>
                                    @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                                        <form method="POST" action="{{ route('groups.dues.payments.confirm', [$group, $payment]) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Konfirmasi</button>
                                        </form>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Belum Bayar</span>
                                    @if($payment->member->user_id === auth()->id())
                                        <form method="POST" action="{{ route('groups.dues.payments.proof', [$group, $payment]) }}" enctype="multipart/form-data" class="flex items-center gap-2">
                                            @csrf
                                            <input type="file" name="proof" accept="image/*,.pdf" class="text-xs text-gray-500" required>
                                            <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Upload Bukti</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ route('groups.dues.index', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Kembali ke daftar iuran</a>
        </div>
    </div>
</x-app-layout>
