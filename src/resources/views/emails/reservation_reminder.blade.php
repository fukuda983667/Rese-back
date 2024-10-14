<!DOCTYPE html>
<html>
<head>
    <title>予約のリマインダー</title>
</head>
<body>
    <h1>予約のリマインダー</h1>
    <p>以下の予約があります:</p>
    <p>ショップ名: {{ $reservation->shop->name }}</p>
    <p>予約日時: {{ $reservation->reservation_time }}</p>
    <p>予約人数: {{ $reservation->reservation_number }}</p>
</body>
</html>