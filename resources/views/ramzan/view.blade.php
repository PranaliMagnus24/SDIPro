<!DOCTYPE html>
<html lang="en">
<head>
    <title>Faizane Sadique</title>
    <style>
         @font-face {
        font-family: "Noto Sans Devanagari";
        src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format("truetype");
    }

    body {
        font-family: "Noto Sans Devanagari", sans-serif;
    }
        /* body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 5mm;
            text-align: center;
            position: relative;
        } */

        body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('{{ public_path("background.jpeg") }}') no-repeat center center fixed;
    background-size: cover; /* Ensures it fills the whole page */
    opacity: 500; /* Adjust for watermark effect (0.1 to 0.3) */
    z-index: -1;
}


        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px auto;
            font-size: 10px;
            border: 1px solid #000;
            background: rgba(255, 255, 255, 0.85); /* Light background for readability */
            border-radius: 10px;
            text-align: center;
        }

        .styled-table td, .styled-table th {
            padding: 8px;
            border: 1px solid #000;
        }

        .styled-table th {
            background-color: #73AD21;
            color: white;
        }

        .curveHead {
            border-radius: 10px;
            background: #73AD21;
            padding: 8px;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        .green-row {
            background: #73AD21;
            color: white;
            font-weight: bold;
            text-align: center;
        }

        .left-align {
            text-align: left;
            padding-left: 10px;
        }

        .qr-container img {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .image-container {
            text-align: center;
            margin-top: 2px;
        }

        .footer-image {
            width: 60%;
            display: block;
            margin: 0 auto;
        }

        .logo-container img {
            width: 60px;
            display: block;
            margin: 0 auto;
        }

        .instagram-link a {
            font-size: 10px;
            font-weight: bold;
            color: blue;
            text-decoration: none;
        }
        
    </style>
</head>
<body>

    <table class="styled-table">
        <!-- Logo Row (Centered Inside Table) -->
        <tr>
            <td colspan="2" class="logo-container">
                <img src="{{ $logoPath }}" alt="Logo">
            </td>
        </tr>

        <!-- Header -->
        <tr><td colspan="2" class="curveHead">{{ $general->title ?? '' }}</td></tr>
        <tr><td colspan="2">{{ $general->subtitle ?? '' }}</td></tr>
        <tr><td colspan="2"><small>{{ $general->address ?? '' }}</small></td></tr>
        <tr><td colspan="2"><small>Mob: {{ $general->contact ?? '' }}</small></td></tr>

        <tr class="green-row">
    <td colspan="3" class="centre-align" style="font-size: 10px;">
        <strong>Ramadan Collection</strong> | <strong>Receipt No:</strong> 2025/{{ $collection->id }} 
        @if($collection->receipt_book)
            ({{ $collection->receipt_book }})
        @endif
    </td>
</tr>


<tr>
<td colspan="3" class="left-align" style="font-size: 16px;">
<strong>Name:</strong> <strong>{{ ucfirst($collection->name) }}</strong>

    </td>
</tr>


        </tr>
        <tr>
            <td class="left-align"><strong>Contact:</strong> {{ $collection->contact }}</td>
            <td class="left-align"><strong>Date:</strong> {{ $collection->date }}</td>
        </tr>
        <tr>
            <td class="left-align"><strong>Donation Category:</strong> {{ $collection->donationcategory }}</td>
            <td class="left-align"><strong>Amount:</strong> Rs.{{ $collection->amount }}</td>
        </tr>
        <tr>
    <td class="left-align"><strong>Payment Mode:</strong> {{ $collection->payment_mode }}</td>

    @if($collection->payment_mode == 'Online')
        <td class="left-align"><strong>Transaction ID:</strong> {{ $collection->transaction_id }}</td>
    @else
        <td></td> 
    @endif
</tr>
        <tr class="green-row">
    <td colspan="2" class="centre-align">
        <strong>Payment Collected By:</strong> {{ Auth::user()->name ?? 'N/A' }}
    </td>
</tr>

<tr>
        <td class="qr-container">
            <strong>Scan to Pay:</strong><br>
            <img src="{{ $qrPath }}" alt="QR Code">
        </td>
        <td class="bank-details left-align" style="font-size: 12px;">
                @if($general->bankdetail)
                    <strong>Bank Details:</strong><br>
                    <small>{!! nl2br(e($general->bankdetail)) !!}</small>
                @endif
            </td>
    </tr>

    <!-- Footer Image -->
    <tr>
        <td colspan="2" class="image-container">
            <img src="{{ $dailyPattiPath }}" alt="Daily Patti" class="footer-image">
        </td>
    </tr>

    <!-- Instagram Link -->
    <tr>
        <td colspan="2" class="instagram-link">
            <a href="https://instagram.com/sdipronasik?igshid=k49z97epdxrn" target="_blank">
                Click Here to Follow Us On Instagram
            </a>
        </td>
    </tr>

   <!-- Notes -->
   @if($general->note)
        <tr class="green-row">
            <td colspan="2">
                <strong>Note:</strong> {{ $general->note }}
            </td>
        </tr>
        @endif

        @if($general->footer)
        <tr class="green-row">
            <td colspan="2">{{ $general->footer }}</td>
        </tr>
        @endif

    </table>

</body>
</html>
