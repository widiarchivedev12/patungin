<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patungin — Catat Patungan & Iuran Bersama</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans bg-slate-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased">

    <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="/" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-900 dark:text-white text-base">Patungin</span>
            </a>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                        Buka Aplikasi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-emerald-700 transition">
                        Daftar Gratis
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        {{-- Hero --}}
        <section class="py-20 text-center px-4">
            <div class="max-w-3xl mx-auto">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-9 h-9 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                    Catat Patungan & Iuran<br>Tanpa Ribet
                </h1>
                <p class="mt-5 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                    Tidak perlu lagi nyari-nyari di chat grup. Catat siapa bayar apa, hitung saldo otomatis, dan tahu persis siapa transfer ke siapa.
                </p>
                <div class="mt-8 flex flex-wrap gap-3 justify-center">
                    @auth
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                            Buat Grup Sekarang →
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                            Mulai Gratis →
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-xl text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 transition">
                            Sudah punya akun
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        {{-- Features --}}
        <section class="py-16 bg-white dark:bg-gray-900 px-4">
            <div class="max-w-5xl mx-auto">
                <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-gray-100 mb-12">Semua yang kamu butuhkan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach([
                        ['icon' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'Hitung Otomatis', 'desc' => 'Pilih cara bagi (sama rata, persentase, atau nominal) dan biarkan aplikasi yang menghitung.'],
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Saldo Real-time', 'desc' => 'Lihat langsung siapa berhutang berapa ke siapa, dengan transfer minimum yang diperlukan.'],
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'title' => 'Iuran Rutin', 'desc' => 'Buat tagihan iuran sekali atau berulang, pantau siapa sudah bayar dan siapa belum.'],
                        ['icon' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13', 'title' => 'Upload Bukti', 'desc' => 'Anggota bisa upload bukti transfer, bendahara konfirmasi dengan satu klik.'],
                        ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Anggota Tamu', 'desc' => 'Orang yang belum punya akun bisa tetap dicatat sebagai anggota grup.'],
                        ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'title' => 'Pengingat Email', 'desc' => 'Sistem kirim otomatis pengingat iuran ke anggota yang belum bayar.'],
                    ] as $feature)
                        <div class="bg-slate-50 dark:bg-gray-800 rounded-xl p-6">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center mb-4">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-20 text-center px-4">
            <div class="max-w-xl mx-auto">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Gratis untuk dipakai</h2>
                <p class="mt-3 text-gray-600 dark:text-gray-400">Daftar sekarang dan buat grup pertama kamu dalam 1 menit.</p>
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center mt-6 px-8 py-3 bg-emerald-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-emerald-700 transition shadow-sm">
                        Daftar Sekarang →
                    </a>
                @endguest
            </div>
        </section>
    </main>

    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} Patungin. Dibuat dengan ❤️
    </footer>
</body>
</html>
