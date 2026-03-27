<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Receipt</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .company-name {
            font-weight: bold;
            font-size: 14px;
        }
        .company-details {
            font-size: 10px;
            color: #666;
            margin-bottom: 10px;
        }
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }
        table.top-details {
            width: 100%;
            margin-bottom: 20px;
        }
        table.top-details td {
            vertical-align: top;
            padding: 2px 0;
        }
        .col-label {
            width: 110px;
            font-weight: bold;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th, table.items td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        table.items th {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .text-left { text-align: left !important; }
        .text-right { text-align: right !important; }
        .font-bold { font-weight: bold; }
        .remarks {
            margin-top: 30px;
            font-size: 10px;
        }
        .bank-details {
            border: 1px solid #000;
            padding: 8px;
            margin-top: 10px;
            width: 60%;
        }
        .thank-you {
            text-align: center;
            font-weight: italic;
            margin-top: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">Tam Durian Farm & Campsite Enterprise</div>
        <div class="company-details">
            (202203319088 MA0295484-D)<br>
            36, Jalan Kampung Ayer Merbau 7, Kampung Kongsai, 77200 Bemban, Melaka<br>
            Email: tamduriancampsite@gmail.com
        </div>
        <div class="receipt-title">OFFICIAL RECEIPT</div>
    </div>

    @php
        $days = \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(\Carbon\Carbon::parse($booking->check_out_date));
        if ($days < 1) $days = 1;
        $guestName = $booking->user->name ?? $booking->customer_name ?? 'Guest';
        $email = $booking->user->email ?? $booking->customer_email ?? '';
    @endphp

    <table class="top-details">
        <tr>
            <td style="width: 60%;">
                <table style="width: 100%;">
                    <tr><td class="col-label">Guest Name :</td><td>{{ $guestName }}</td></tr>
                    <tr><td class="col-label">Contact No. :</td><td>{{ $booking->customer_phone ?? 'N/A' }}</td></tr>
                    <tr><td class="col-label">Check-in date :</td><td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('l, F d, Y') }}</td></tr>
                    <tr><td class="col-label">Check-out date :</td><td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('l, F d, Y') }}</td></tr>
                </table>
            </td>
            <td style="width: 40%;">
                <table style="width: 100%;">
                    <tr><td class="col-label">Date :</td><td>{{ $booking->updated_at->format('d/m/Y') }}</td></tr>
                    <tr><td class="col-label">Official Receipt No. :</td><td>TDFC{{ $booking->updated_at->format('ymd') }}{{ $booking->id }}</td></tr>
                    <tr><td class="col-label">Currency :</td><td>MYR</td></tr>
                    <tr><td class="col-label">Email :</td><td>{{ $email }}</td></tr>
                    <tr><td class="col-label">Tent No. :</td><td>{{ $booking->slot->tent->name ?? 'Standard Tent' }}</td></tr>
                    <tr><td class="col-label">No. of Tent :</td><td>1</td></tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%;">S/N</th>
                <th style="width: 15%;">PACKAGE TYPE</th>
                <th class="text-left" style="width: 35%;">DESCRIPTION</th>
                <th style="width: 10%;">NO. OF TENT</th>
                <th style="width: 10%;">NO. NIGHT</th>
                <th style="width: 10%;">PRICE/NIGHT</th>
                <th class="text-right" style="width: 15%;">TOTAL AMOUNT</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $booking->slot->tent->name ?? 'Tent' }}</td>
                <td class="text-left">
                    <span class="font-bold">Package details:</span><br>
                    Campsite booking for {{ $days }} night(s).
                </td>
                <td>1</td>
                <td>{{ $days }}</td>
                <td>Lump Sum<br>{{ number_format($booking->total_price, 2) }}</td>
                <td class="text-right">{{ number_format($booking->total_price, 2) }}</td>
            </tr>
            <!-- Totals -->
            <tr>
                <td colspan="6" class="text-left font-bold border-0">Grand Total Amount</td>
                <td class="text-right font-bold">{{ number_format($booking->total_price, 2) }}</td>
            </tr>
            <tr>
                <td colspan="6" class="text-left border-0">Less: Payment received on {{ $booking->updated_at->format('d/m/Y') }}</td>
                <td class="text-right">({{ number_format($booking->total_price, 2) }})</td>
            </tr>
            <tr>
                <td colspan="6" class="text-left font-bold border-0" style="border-top: 1px solid #000;">Balance amount</td>
                <td class="text-right font-bold" style="border-top: 1px solid #000;">Zero balance</td>
            </tr>
        </tbody>
    </table>

    <div class="remarks">
        <strong>Remarks :</strong><br>
        1 Check-in time : 3:00 PM<br>
        2 Check-out time : 12:00 PM<br>
        3 Please send your payment via online bank transfer (Duitnow) and quote our reference no. Our bank details are as follows :
    </div>

    <div class="bank-details">
        TAM DURIAN FARM AND CAMPSITE ENTERPRISE<br>
        AFFIN BANK<br>
        100090166702<br>
        PHBMMYKLXXX
    </div>

    <div class="thank-you">
        <em>Thank you for your business!</em>
    </div>

</body>
</html>
