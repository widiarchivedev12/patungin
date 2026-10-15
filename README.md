# Patungin

Aplikasi web untuk mencatat patungan (split-bill) dan iuran kelompok. Dibangun dengan Laravel 13.

## Fitur

- **Grup Patungan** — Catat pengeluaran bersama, pilih cara bagi (sama rata, persentase, nominal, atau per item). Saldo dihitung otomatis dan dioptimasi menjadi transfer minimum antar anggota.
- **Grup Iuran** — Buat tagihan iuran sekali atau berulang (mingguan/bulanan/tahunan). Anggota upload bukti bayar, admin konfirmasi dengan satu klik.
- **Pelunasan** — Catat pembayaran hutang antar anggota beserta bukti transfer.
- **Anggota Tamu** — Orang tanpa akun dapat dicatat sebagai anggota grup.
- **Undangan via Kode** — Bagikan kode undangan untuk mengajak anggota bergabung.
- **Pengingat Email** — Kirim otomatis pengingat iuran ke anggota yang belum bayar.

## Teknologi

| Layer | Stack |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Database | SQLite (lokal), kompatibel MySQL/PostgreSQL |
| Frontend | Blade, Tailwind CSS v3, Alpine.js v3 |
| Email | Laravel Mail (konfigurasi SMTP) |
| Storage | Laravel Storage (bukti transfer) |

## Instalasi

### Prasyarat

- PHP 8.2+
- Composer
- Node.js 18+

### Langkah

```bash
# Clone & install dependensi
git clone <repo-url>
cd patungin
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Database
touch database/database.sqlite
php artisan migrate

# Build aset
npm run build

# Jalankan server
php artisan serve
```

Buka [http://localhost:8000](http://localhost:8000).

### Development

```bash
# Jalankan Vite dev server + Laravel sekaligus
npm run dev          # terminal 1
php artisan serve    # terminal 2
```

## Konfigurasi Email

Edit `.env` untuk mengaktifkan pengiriman email:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@patungin.app
MAIL_FROM_NAME="${APP_NAME}"
```

Selama development, gunakan `MAIL_MAILER=log` untuk melihat email di `storage/logs/laravel.log`.

## Struktur Modul

```
app/
├── Models/
│   ├── Group          — Grup (patungan / iuran)
│   ├── Member         — Anggota grup (user atau tamu)
│   ├── Expense        — Pengeluaran dalam grup patungan
│   ├── ExpenseShare   — Porsi tiap anggota per pengeluaran
│   ├── Due            — Tagihan iuran
│   ├── DuePayment     — Status bayar tiap anggota per tagihan
│   └── Settlement     — Catatan pelunasan hutang
├── Http/Controllers/
│   ├── GroupController
│   ├── ExpenseController
│   ├── DueController
│   ├── DuePaymentController
│   └── SettlementController
└── Mail/
    └── DueReminder    — Email pengingat iuran
```

## Tipe Grup

| Nilai DB | Tampilan | Deskripsi |
|---|---|---|
| `patungan` | Patungan | Split-bill pengeluaran bersama |
| `iuran` | Iuran | Tagihan rutin yang wajib dibayar tiap anggota |

## Lisensi

MIT
