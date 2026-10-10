<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Iuran</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; }
        .header { background: #4f46e5; padding: 28px 32px; color: #fff; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 4px 0 0; opacity: 0.85; font-size: 14px; }
        .body { padding: 28px 32px; }
        .amount { font-size: 28px; font-weight: bold; color: #4f46e5; margin: 16px 0; }
        .detail { background: #f9fafb; border-radius: 8px; padding: 16px; margin: 16px 0; }
        .detail p { margin: 4px 0; font-size: 14px; color: #374151; }
        .detail strong { color: #111827; }
        .footer { padding: 20px 32px; background: #f9fafb; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af; }
        .cta { display: inline-block; background: #4f46e5; color: #fff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: bold; margin: 16px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Pengingat Iuran</p>
        </div>
        <div class="body">
            <p>Halo <strong>{{ $member->display_name }}</strong>,</p>

            @if($tone === 'santai')
                <p>Eh, ada iuran yang belum dibayar nih. Cek yuk 😊</p>
            @elseif($tone === 'formal')
                <p>Kami ingin mengingatkan bahwa terdapat iuran yang belum diselesaikan.</p>
            @else
                <p>Iuran kamu sudah melewati jatuh tempo. Mohon segera dilunasi.</p>
            @endif

            <p class="amount">Rp {{ number_format($due->amount, 0, ',', '.') }}</p>

            <div class="detail">
                <p><strong>Nama Iuran:</strong> {{ $due->title }}</p>
                <p><strong>Grup:</strong> {{ $group->name }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ $due->due_date->format('d M Y') }}</p>
            </div>

            <a href="{{ route('groups.dues.show', [$group, $due]) }}" class="cta">Lihat Detail &amp; Upload Bukti</a>

            @if($tone === 'santai')
                <p style="font-size:13px;color:#6b7280;">Transfer ke rekening bendahara ya, lalu upload buktinya di link di atas.</p>
            @endif
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh {{ config('app.name') }}. Jika sudah membayar, abaikan email ini.</p>
        </div>
    </div>
</body>
</html>
