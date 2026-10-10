<x-app-layout>
    <x-slot name="title">Pelunasan — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Pelunasan — {{ $group->name }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Saran transfer --}}
            @if(! empty($simplifiedDebts))
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-5">
                    <h3 class="font-semibold text-blue-900 dark:text-blue-200 mb-3">Transfer yang Direkomendasikan</h3>
                    <div class="space-y-2">
                        @foreach($simplifiedDebts as $debt)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-800 dark:text-blue-200">
                                    <strong>{{ $members[$debt['from']]?->display_name }}</strong> transfer ke <strong>{{ $members[$debt['to']]?->display_name }}</strong>
                                </span>
                                <span class="font-bold text-blue-900 dark:text-blue-100">Rp {{ number_format($debt['amount'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-5 text-center">
                    <p class="text-green-800 dark:text-green-200 font-medium">🎉 Semua sudah lunas!</p>
                </div>
            @endif

            {{-- Form catat pelunasan --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Catat Pelunasan Manual</h3>
                <form method="POST" action="{{ route('groups.settlements.store', $group) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="from_member_id" value="Dari (yang bayar)" />
                            <select id="from_member_id" name="from_member_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih anggota --</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('from_member_id') == $member->id ? 'selected' : '' }}>{{ $member->display_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('from_member_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="to_member_id" value="Ke (yang menerima)" />
                            <select id="to_member_id" name="to_member_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- Pilih anggota --</option>
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
                            <input type="file" id="proof" name="proof" accept="image/*,.pdf" class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <x-input-error :messages="$errors->get('proof')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="note" value="Catatan (opsional)" />
                            <x-text-input id="note" name="note" type="text" class="mt-1 block w-full" :value="old('note')" placeholder="Contoh: Transfer BCA 10 Okt" />
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <x-primary-button>Catat Pelunasan</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Riwayat pelunasan --}}
            @if($settlements->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">Riwayat Pelunasan</h3>
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($settlements as $settlement)
                            <div class="flex items-center justify-between p-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $settlement->fromMember?->display_name }} → {{ $settlement->toMember?->display_name }}
                                    </p>
                                    @if($settlement->note)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $settlement->note }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format($settlement->amount, 0, ',', '.') }}</span>
                                    @if($settlement->status === 'confirmed')
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Terkonfirmasi</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200">Menunggu</span>
                                        @if(auth()->user()->groupMemberships->where('group_id', $group->id)->first()?->isAdmin())
                                            <form method="POST" action="{{ route('groups.settlements.confirm', [$group, $settlement]) }}">
                                                @csrf
                                                <button type="submit" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Konfirmasi</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('groups.settlements.destroy', [$group, $settlement]) }}" onsubmit="return confirm('Hapus catatan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <a href="{{ route('groups.show', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Kembali ke grup</a>
        </div>
    </div>
</x-app-layout>
