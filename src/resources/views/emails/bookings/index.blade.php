<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <title>Booking {{ $status == 'approved' ? 'Disetujui' : 'Ditolak' }}</title>

    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { width: 90%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .content { margin-top: 20px; }
        ul { list-style-type: none; padding: 0; }
        li { margin-bottom: 10px; }
        strong { color: #333; }
        .header-approved { font-size: 24px; color: #4CAF50; }
        .header-rejected { font-size: 24px; color: #E53E3E; }
    </style>
</head>
<body>
<div class="container">

    @if ($status === 'approved')
        <h1 class="header-approved">Booking Disetujui!</h1>
        <div class="content">
            <p>Halo, <strong>{{ $booking->user->name }}</strong>!</p>
            <p>Booking ruangan Anda telah disetujui oleh Admin.</p>
        </div>
    @else
        <h1 class="header-rejected">Booking Ditolak</h1>
        <div class="content">
            <p>Halo, <strong>{{ $booking->user->name }}</strong>.</p>
            <p>Mohon maaf, pengajuan booking ruangan Anda tidak dapat kami setujui saat ini.</p>
        </div>
    @endif

    <hr>
    <h3>Detail Pengajuan:</h3>
    <ul>
        <li><strong>Ruangan:</strong> {{ $booking->room->name }} ({{ $booking->room->gedung }}, Lt. {{ $booking->room->lantai }})</li>
        <li><strong>Kegiatan:</strong> {{ $booking->title }}</li>
        <li><strong>Waktu:</strong> {{ $booking->start_time->format('d F Y, H:i') }} - {{ $booking->end_time->format('H:i') }}</li>
    </ul>

</div>
</body>
</html>
