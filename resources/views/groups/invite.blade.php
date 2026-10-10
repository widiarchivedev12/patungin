<x-app-layout>
    <x-slot name="title">Undang Anggota — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Undang Anggota ke {{ $group->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Kode undangan --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Kode Undangan</h3>
                <div class="flex items-center gap-3">
                    <span class="flex-1 text-center text-3xl font-mono font-bold tracking-widest text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 py-3 rounded-lg">
                        {{ $group->invite_code }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Bagikan kode ini ke orang yang ingin diundang. Mereka bisa masuk lewat menu "Masuk Grup".</p>

                <form method="POST" action="{{ route('groups.invite.regenerate', $group) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('Kode lama tidak akan bisa dipakai lagi. Lanjutkan?')">
                        Buat kode baru
                    </button>
                </form>
            </div>

            {{-- Tambah anggota tamu --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Tambah Anggota Tamu</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Untuk orang yang tidak punya akun. Bisa dilunasi atas nama mereka.</p>
                <form method="POST" action="{{ route('groups.members.guest', $group) }}" class="flex gap-3">
                    @csrf
                    <x-text-input name="display_name" type="text" class="flex-1" placeholder="Nama tamu" required />
                    <x-primary-button type="submit">Tambah</x-primary-button>
                </form>
                <x-input-error :messages="$errors->get('display_name')" class="mt-2" />
            </div>

            <div>
                <a href="{{ route('groups.show', $group) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">← Kembali ke grup</a>
            </div>
        </div>
    </div>
</x-app-layout>
