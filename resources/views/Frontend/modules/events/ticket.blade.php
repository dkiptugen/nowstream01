<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 15px;
        }

        .ticket {
            border: 2px dashed #000;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
        }

        .section {
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
        }

        .qr {
            text-align: center;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="ticket">

    <div class="header">
        <div class="title">{{ $event->title }}</div>
        <div>{{ $event->location }}</div>
    </div>

    <div class="section">
        <span class="label">Name:</span>
        {{ $user->name }}
    </div>

    <div class="section">
        <span class="label">Ticket No:</span>
        {{ $ticket->uuid }}
    </div>

    <div class="section">
        <span class="label">Date:</span>
        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
    </div>

    <div class="section">
        <span class="label">Time:</span>
        {{ \Carbon\Carbon::parse($event->start_date)->format('H:i') }}
    </div>

    <div class="section">
        <span class="label">Type:</span>
        {{ $ticket->type ?? 'Standard' }}
    </div>

    <div class="qr">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ $qrData }}">
    </div>

    <div class="footer">
        Present this ticket at entry<br>
        Powered by Streamer.co.ke
    </div>

</div>

</body>
</html>
