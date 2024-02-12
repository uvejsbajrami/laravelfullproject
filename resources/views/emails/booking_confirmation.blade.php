<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmation</h1>
    <p>Thank you for booking with us. Here are your booking details:</p>

    <p>Hotel: {{ $booking->hotel->name }}</p>
    <p>Room: {{ $booking->room_number }}</p>
    <p>Check-In Date: {{ $booking->CheckInDate }}</p>
    <p>Check-Out Date: {{ $booking->CheckOutDate }}</p>

    <p>Have a great stay!</p>
</body>
</html>
