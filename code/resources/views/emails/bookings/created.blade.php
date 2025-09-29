<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
<h1>Hello {{ $booking->customer->name }},</h1>
<p>Your booking has been successfully created!</p>

<p><strong>Service:</strong> {{ $booking->service->name }}</p>
<p><strong>Date:</strong> {{ $booking->start_time->format('d M Y H:i') }}</p>
<p><strong>Provider:</strong> {{ $booking->provider->name }}</p>

<p>Thank you for booking with us.</p>
</body>
</html>
