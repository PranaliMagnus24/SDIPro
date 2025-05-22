<!DOCTYPE html>
<html lang="en">
<head>
    <title>Faizane Sadique</title>
    <style>
    @font-face
    {
        font-family: "Noto Sans Devanagari";
        src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format("truetype");
    }
    body
    {
        font-family: "Noto Sans Devanagari", sans-serif;
    }
    body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: white;
    background-size: cover;
    opacity: 0.5;
    z-index: -1;
}

    .styled-table
    {
        width: 100%;
        border-collapse: collapse;
        margin: 5px auto;
        font-size: 10px;
        border: 1px solid #000;
        background: rgba(255, 255, 255, 0.85);
        border-radius: 10px;
        text-align: center;
    }
    .styled-table td, .styled-table th
    {
        padding: 4px;
        border: 1px solid #000;
    }
    .styled-table th
    {
        background-color: #73AD21;
        color: white;
    }
    .curveHead
    {
        border-radius: 10px;
        background: #73AD21;
        padding: 8px;
        color: #ffffff;
        font-weight: bold;
        text-align: center;
    }
    .green-row
    {
        background: #73AD21;
        color: white;
        font-weight: bold;
        text-align: center;
    }
    .left-align
    {
        text-align: left;
        padding-left: 10px;
    }
    .qr-container img {
    height: 90px;
    width: 90px;
    display: block;
    margin: 0 auto;
    object-fit: contain;
}

    .image-container
    {
        text-align: center;
        margin-top: 1px;
    }
    .footer-image {
    width: 60%;
    height: 50px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}

    .logo-container img
    {
        width: 60px;
        display: block;
        margin: 0 auto;
    }
    .instagram-link a
    {
        font-size: 10px;
        font-weight: bold;
        color: blue;
        text-decoration: none;
    }
    </style>
</head>
<body>
    <table class="styled-table" style="border: 1px solid black;">
        <!----------Heade logo------->
        <tr>
            <td colspan="2" style="padding: 10px;">
                <div style="display: flex; align-items: center; justify-content: center; position: relative;">
                    <!-- Logo (absolute left) -->
                    <div style="position: absolute; left: 0;">
                        <img src="{{ $logoPath }}" alt="Logo" style="height: 50px; width: 50px;">
                    </div>
                    <!-- Centered Content -->
                    <div style="text-align: center; width: 100%;">
                        <strong>{{ $general->title ?? '' }}</strong><br>
                        {{ $general->subtitle ?? '' }}<br>
                        <small>{{ $general->address ?? '' }}</small><br>
                        <small>{{ $general->contact ?? '' }}</small>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="green-row">
            <td colspan="2" style="font-size: 10px;">
                <strong>Bagair Gosht Wali Hisso Ki Qurbani</strong> |
                <strong>Receipt No:</strong> 2025/{{ $qurbani->id }}
                @if($qurbani->receipt_book) ({{ $qurbani->receipt_book }}) @endif
            </td>
        </tr>
        <tr>
            <td class="left-align" colspan="2">
                <strong>Name:</strong> <strong>{{ ucfirst($qurbani->contact_name) }}</strong>
            </td>
        </tr>
        <tr>
            <td class="left-align">
                <strong>Contact:</strong> {{ $qurbani->mobile }}
                @if(!empty($qurbani->alternative_mobile))
                / {{ $qurbani->alternative_mobile }}
                @endif
            </td>
            <td class="left-align"><strong>Date:</strong> {{ $qurbani->created_at->format('d-m-Y') }} &nbsp; &nbsp; <strong>{{ ucfirst($qurbani->qurbani_days)}}</strong>
            </td>
        </tr>
        <tr>
            <td class="left-align"><strong>Payment Mode:</strong> {{ $qurbani->payment_type }}</td>
            @if($qurbani->payment_type == 'Online')
            <td class="left-align"><strong>Transaction ID:</strong> {{ $qurbani->transaction_number }}</td>
            @else
            <td></td>
            @endif
        </tr>
        <tr>
            <td colspan="2">
                <table class="styled-table" style="margin-top: 1px;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">No</th>
                            <th style="width: 60%;">Name</th>
                            <th style="width: 30%;">Hissa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalHissa = 0; @endphp
                        @foreach($qurbanihisse as $index => $hissa)
                        @php
                        $hissaCount = 1;
                        $displayName = $hissa->name;
                        if ($hissa->aqiqah == 1) {
                            if ($hissa->gender == 'Male') {
                                $displayName .= ' (Aqiqah Male)';
                                $hissaCount = 1;
                            } elseif ($hissa->gender == 'Female') {
                                $displayName .= ' (Aqiqah Female)';
                                $hissaCount = 1;
                            }
                        }
                        $totalHissa += $hissaCount;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="left-align">{{ $displayName }}</td>
                            <td>{{ $hissaCount }}</td>
                        </tr>
                        @endforeach
                        <tr class="green-row">
                            <td colspan="2"><strong>Total Hissa</strong></td>
                            <td><strong>{{ $totalHissa }}</strong></td>
                        </tr>
                        <tr class="green-row">
                            <td colspan="2">Total Amount</td>
                            <td>{{$qurbani->total_amount}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>

        <tr class="green-row">
            <td class="centre-align" colspan="2">
                <strong>Payment Collected By:</strong> {{ Auth::user()->name ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="qr-container">
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
                <img src="{{ $footerImgPath }}" alt="Footer Image" class="footer-image">
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
                {{ $general->note }}
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
