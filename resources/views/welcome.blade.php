<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patungan — Catat Patungan & Iuran Bersama</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100 antialiased">

    <nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <span class="font-bold text-xl text-indigo-600 dark:text-indigo-400">💰 Patungan</span>
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
                        Buka Aplikasi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition">
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
                <div class="text-6xl mb-6">💰</div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-gray-100 leading-tight">
                    Catat Patungan & Iuran<br>Tanpa Ribet
                </h1>
                <p class="mt-5 text-lg text-gray-600 dark:text-gray-400 max-w-xl mx-auto">
                    Tidak perlu lagi nyari-nyari di chat grup. Catat siapa bayar apa, hitung saldo otomatis, dan tahu persis siapa transfer ke siapa.
                </p>
                <div class="mt-8 flex flex-wrap gap-3 justify-center">
                    @auth
                        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
                            Buat Grup Sekarang →
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
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
                        ['icon' => '🧮', 'title' => 'Hitung Otomatis', 'desc' => 'Pilih cara bagi (sama rata, persentase, atau nominal) dan biarkan aplikasi yang menghitung.'],
                        ['icon' => '📊', 'title' => 'Saldo Real-time', 'desc' => 'Lihat langsung siapa berhutang berapa ke siapa, dengan transfer minimum yang diperlukan.'],
                        ['icon' => '🧾', 'title' => 'Iuran Rutin', 'desc' => 'Buat tagihan iuran sekali atau berulang, pantau siapa sudah bayar dan siapa belum.'],
                        ['icon' => '📎', 'title' => 'Upload Bukti', 'desc' => 'Anggota bisa upload bukti transfer, bendahara konfirmasi dengan satu klik.'],
                        ['icon' => '👤', 'title' => 'Anggota Tamu', 'desc' => 'Orang yang belum punya akun bisa tetap dicatat sebagai anggota grup.'],
                        ['icon' => '🔔', 'title' => 'Pengingat Email', 'desc' => 'Sistem kirim otomatis pengingat iuran ke anggota yang belum bayar.'],
                    ] as $feature)
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                            <div class="text-3xl mb-3">{{ $feature['icon'] }}</div>
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $feature['desc'] }}</p>
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
                    <a href="{{ route('register') }}" class="inline-flex items-center mt-6 px-8 py-3 bg-indigo-600 border border-transparent rounded-xl text-base font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
                        Daftar Sekarang →
                    </a>
                @endguest
            </div>
        </section>
    </main>

    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
        © {{ date('Y') }} Patungan. Dibuat dengan ❤️
    </footer>
</body>
</html>
