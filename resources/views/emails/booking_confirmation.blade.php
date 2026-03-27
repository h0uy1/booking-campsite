<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f5f5f4; margin: 0; padding: 20px;">
    @php
        $guestName = $booking->user->name ?? $booking->customer_name ?? 'Guest';
    @endphp
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Header -->
        <div style="background-color: #064e3b; padding: 30px 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 300; letter-spacing: 1px;">Booking Confirmed!</h1>
        </div>
        
        <!-- Content -->
        <div style="padding: 30px 20px; color: #1c1917;">
            <p style="font-size: 16px; line-height: 1.5;">Dear {{ $guestName }},</p>
            <p style="font-size: 16px; line-height: 1.5;">Thank you for reserving your getaway with <strong>Tam Durian Farm & Campsite</strong>. We are thrilled to host you and have attached your official receipt to this email.</p>
            
            <div style="background-color: #fafaf9; border: 1px solid #e7e5e4; border-radius: 8px; padding: 20px; margin: 25px 0;">
                <h2 style="font-size: 18px; margin-top: 0; color: #064e3b; border-bottom: 1px solid #e7e5e4; padding-bottom: 10px;">Booking Details</h2>
                <table style="width: 100%; font-size: 14px; line-height: 1.6;">
                    <tr><td style="color: #78716c; width: 120px; padding-bottom: 5px;">Booking ID:</td><td style="font-weight: bold; padding-bottom: 5px;">#BK-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td></tr>
                    <tr><td style="color: #78716c; padding-bottom: 5px;">Package:</td><td style="font-weight: bold; padding-bottom: 5px;">{{ $booking->slot->tent->name ?? 'Campsite Tent' }}</td></tr>
                    <tr><td style="color: #78716c; padding-bottom: 5px;">Dates:</td><td style="font-weight: bold; padding-bottom: 5px;">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td></tr>
                    <tr><td style="color: #78716c; padding-bottom: 5px;">Check-in:</td><td style="font-weight: bold; padding-bottom: 5px;">After 3:00 PM</td></tr>
                    <tr><td style="color: #78716c; padding-bottom: 5px;">Check-out:</td><td style="font-weight: bold; padding-bottom: 5px;">Before 12:00 PM</td></tr>
                    <tr><td style="color: #78716c; padding-bottom: 0;">Amount Paid:</td><td style="font-weight: bold; padding-bottom: 0;">RM {{ number_format($booking->total_price, 2) }}</td></tr>
                </table>
            </div>

            <h3 style="font-size: 16px; color: #064e3b; margin-top: 30px;">Campsite Policies & Important Info</h3>
            <ul style="font-size: 14px; line-height: 1.6; color: #44403c; padding-left: 20px;">
                <li style="margin-bottom: 8px;"><strong>Arrival:</strong> Please present your Booking ID upon arrival.</li>
                <li style="margin-bottom: 8px;"><strong>Check-out:</strong> Strictly at 12:00 PM to allow us to prepare for the next guests.</li>
                <li style="margin-bottom: 8px;"><strong>Quiet Hours:</strong> Between 10:00 PM and 7:00 AM. Please respect the tranquility of nature and other campers.</li>
                <li style="margin-bottom: 8px;"><strong>Cleanliness:</strong> Please dispose of all trash in the designated bins provided throughout the campsite.</li>
                <li style="margin-bottom: 8px;"><strong>Fires:</strong> Campfires are only permitted in designated fire pits.</li>
            </ul>

            <p style="font-size: 14px; line-height: 1.6; color: #78716c; margin-top: 30px;">
                If you have any questions or require modifications to your booking, please don't hesitate to reply to this email or contact us at tamduriancampsite@gmail.com.
            </p>

            <p style="font-size: 16px; line-height: 1.5; margin-top: 30px;">
                Warm regards,<br>
                <strong>The Tam Durian Farm Team</strong>
            </p>
        </div>
        
        <!-- Footer -->
        <div style="background-color: #fafaf9; border-top: 1px solid #e7e5e4; padding: 20px; text-align: center; color: #a8a29e; font-size: 12px;">
            &copy; {{ date('Y') }} Tam Durian Farm & Campsite Enterprise.<br>
            All rights reserved.
        </div>
    </div>
</body>
</html>
