<x-app-layout>
    <x-slot name="title">Undang Anggota — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('groups.show', $group) }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Undang Anggota ke {{ $group->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Kode undangan --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Kode Undangan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Bagikan kode ini ke orang yang ingin diundang. Mereka bisa masuk lewat menu "Masuk Grup".</p>
                <div class="flex items-center justify-center bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl py-5 mb-4">
                    <span class="text-4xl font-mono font-bold tracking-widest text-emerald-600 dark:text-emerald-400 select-all">
                        {{ $group->invite_code }}
                    </span>
                </div>

                <form method="POST" action="{{ route('groups.invite.regenerate', $group) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Kode lama tidak akan bisa dipakai lagi. Lanjutkan?')"
                        class="inline-flex items-center gap-1.5 text-sm text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Buat kode baru
                    </button>
                </form>
            </div>

            {{-- Tambah anggota tamu --}}
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Tambah Anggota Tamu</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Untuk orang yang tidak punya akun. Bisa dilunasi atas nama mereka.</p>
                <form method="POST" action="{{ route('groups.members.guest', $group) }}" class="flex gap-3">
                    @csrf
                    <x-text-input name="display_name" type="text" class="flex-1" placeholder="Nama tamu" required />
                    <x-primary-button type="submit">Tambah</x-primary-button>
                </form>
                <x-input-error :messages="$errors->get('display_name')" class="mt-2" />
            </div>

        </div>
    </div>
</x-app-layout>
