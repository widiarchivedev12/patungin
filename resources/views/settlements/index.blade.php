<x-app-layout>
    <x-slot name="title">Pelunasan — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('groups.show', $group) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $group->name }}</p>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Pelunasan</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Saran transfer --}}
            @if(! empty($simplifiedDebts))
                <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="font-semibold text-emerald-900 dark:text-emerald-200">Transfer yang Direkomendasikan</h3>
                    </div>
                    <div class="space-y-2">
                        @foreach($simplifiedDebts as $debt)
                            <div class="flex items-center justify-between bg-white dark:bg-emerald-900/30 rounded-lg px-4 py-2.5">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $members[$debt['from']]?->display_name }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $members[$debt['to']]?->display_name }}</span>
                                </div>
                                <span class="font-bold text-red-600 dark:text-red-400 tabular-nums text-sm">Rp {{ number_format($debt['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-5">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-emerald-800 dark:text-emerald-200">Semua sudah lunas!</p>
                        <p class="text-sm text-emerald-700 dark:text-emerald-300">Tidak ada utang yang perlu diselesaikan di grup ini.</p>
                    </div>
                </div>
            @endif

            {{-- Form catat pelunasan --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Catat Pelunasan</h3>
                <form method="POST" action="{{ route('groups.settlements.store', $group) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="from_member_id" value="Dari (yang bayar)" />
                            <select id="from_member_id" name="from_member_id" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-emerald-500 rounded-lg text-sm">
                                <option value="">— Pilih anggota —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('from_member_id') == $member->id ? 'selected' : '' }}>{{ $member->display_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('from_member_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="to_member_id" value="Ke (yang menerima)" />
                            <select id="to_member_id" name="to_member_id" required
                                class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-emerald-500 rounded-lg text-sm">
                                <option value="">— Pilih anggota —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('to_member_id') == $member->id ? 'selected' : '' }}>{{ $member->display_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('to_member_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="amount" value="Jumlah (Rp)" />
                            <x-text-input id="amount" name="amount" type="number" class="mt-1 block w-full" :value="old('amount')" min="1" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="proof" value="Bukti Transfer (opsional)" />
                            <input type="file" id="proof" name="proof" accept="image/*,.pdf"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                       file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0
                                       file:text-sm file:font-medium file:bg-emerald-50 file:text-emerald-700
                                       hover:file:bg-emerald-100 dark:file:bg-emerald-900/30 dark:file:text-emerald-300
                                       cursor-pointer">
                            <x-input-error :messages="$errors->get('proof')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="note" value="Catatan (opsional)" />
                            <x-text-input id="note" name="note" type="text" class="mt-1 block w-full" :value="old('note')" placeholder="Contoh: Transfer BCA 10 Okt" />
                        </div>
                    </div>
                    <div class="mt-5 flex justify-end">
                        <x-primary-button>Catat Pelunasan</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Riwayat pelunasan --}}
            @if($settlements->isNotEmpty())
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Riwayat Pelunasan</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($settlements as $settlement)
                            <div class="flex items-center justify-between p-4 gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $settlement->fromMember?->display_name }}
                                        <span class="text-gray-400 mx-1">→</span>
                                        {{ $settlement->toMember?->display_name }}
                                    </p>
                                    @if($settlement->note)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $settlement->note }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100 tabular-nums text-sm">Rp {{ number_format($settlement->amount, 0, ',', '.') }}</span>
                                    @if($settlement->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Terkonfirmasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">Menunggu</span>
                                        @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                                            <form method="POST" action="{{ route('groups.settlements.confirm', [$group, $settlement]) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-medium">Konfirmasi</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('groups.settlements.destroy', [$group, $settlement]) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 dark:text-red-400 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
