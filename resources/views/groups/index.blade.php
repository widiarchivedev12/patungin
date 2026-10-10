<x-app-layout>
    <x-slot name="title">Grup Saya</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Grup Saya</h2>
            <div class="flex gap-2">
                <a href="{{ route('groups.join') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Masuk Grup
                </a>
                <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                    + Buat Grup
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($groups->isEmpty())
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">💰</div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Belum ada grup</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Buat grup baru atau masuk ke grup yang sudah ada.</p>
                    <div class="mt-6 flex gap-3 justify-center">
                        <a href="{{ route('groups.join') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                            Masuk dengan Kode
                        </a>
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                            Buat Grup Baru
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 transition">
                            <div class="p-6">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full mb-2 {{ $group->type === 'patungan' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' }}">
                                            {{ $group->type === 'patungan' ? 'Patungan' : 'Iuran' }}
                                        </span>
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg">{{ $group->name }}</h3>
                                        @if($group->description)
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $group->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span>{{ $group->members->count() }} anggota</span>
                                    <span>·</span>
                                    <span>{{ $group->expenses->count() }} pengeluaran</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
