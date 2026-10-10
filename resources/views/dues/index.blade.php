<x-app-layout>
    <x-slot name="title">Daftar Iuran — {{ $group->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Iuran — {{ $group->name }}</h2>
            <a href="{{ route('groups.dues.create', $group) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                + Buat Iuran
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($dues->isEmpty())
                <div class="text-center py-16 text-gray-500 dark:text-gray-400">
                    <p class="text-lg">Belum ada iuran dibuat.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($dues as $due)
                        <a href="{{ route('groups.dues.show', [$group, $due]) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $due->title }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Rp {{ number_format($due->amount, 0, ',', '.') }} · Jatuh tempo {{ $due->due_date->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $total = $due->payments->count();
                                        $paid = $due->payments->where('status', 'paid')->count();
                                    @endphp
                                    <span class="text-sm font-semibold {{ $paid === $total ? 'text-green-600 dark:text-green-400' : 'text-amber-600 dark:text-amber-400' }}">
                                        {{ $paid }}/{{ $total }} lunas
                                    </span>
                                    <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1.5">
                                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $total > 0 ? ($paid/$total*100) : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
