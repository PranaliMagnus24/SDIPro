<!DOCTYPE html>
<html>
<head>
    <title>Qurbani Receipt</title>
    <style>
        @font-face {
            font-family: "Noto Sans Devanagari";
            src: url("{{ storage_path('fonts/NotoSansDevanagari-Regular.ttf') }}") format("truetype");
        }

        body {
            font-family: "Noto Sans Devanagari", sans-serif;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px auto;
            font-size: 10px;
            border: 1px solid #000;
            text-align: center;
        }

        .styled-table td, .styled-table th {
            padding: 6px;
            border: 1px solid #000;
        }

        .styled-table th {
            background-color: #73AD21;
            color: white;
        }

        .curveHead {
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
        }

        .left-align {
            text-align: left;
            padding-left: 10px;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
<!-- @php
    $logoFullPath = asset('general/' . $general->logo);
@endphp -->
@php
    $path = public_path('general/' . $general->logo);
    $logoBase64 = '';
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
@endphp
<table class="styled-table">
    <!-- <tr>
        <td colspan="3" style="text-align: center;">
            <img src="{{ asset('general/' . $general->logo) }}" alt="Logo" style="max-width: 60px;">
            <p><strong>Logo Path:</strong> {{ asset('general/' . $general->logo) }}</p>
        </td>
    </tr> -->

    @if($logoBase64)
    <img src="{{ $logoBase64 }}" alt="Logo" style="max-width: 60px;">
@else
    <p><strong>Logo not found.</strong></p>
@endif

        </td>
    </tr>
    <tr>
        <td colspan="3" class="curveHead">{{ $general->title ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3">{{ $general->subtitle ?? '' }}</td>
    </tr>
    <tr>
        <td colspan="3"><small>{{ $general->address ?? '' }}</small></td>
    </tr>
    <tr>
        <td colspan="3"><small>Mob: {{ $general->contact ?? '' }}</small></td>
    </tr>
    <tr class="green-row">
        <td colspan="3" style="font-size: 10px;">
            <strong>Bagair Gosht Wali Hisso Ki Qurbani</strong> |
            <strong>Receipt No:</strong> 2025/{{ $qurbani->id }}
            @if($qurbani->receipt_book) ({{ $qurbani->receipt_book }}) @endif
        </td>
    </tr>
    <tr>
        <td class="left-align" colspan="2"><strong>Name:</strong> {{ $qurbani->contact_name }}</td>
        <td class="left-align"><strong>Mobile:</strong> {{ $qurbani->mobile }}</td>
    </tr>
    <tr>
        <td class="left-align" colspan="2"><strong>Address:</strong> Nashik</td>
        <td class="left-align"><strong>Date:</strong> {{ $qurbani->created_at->format('d-m-Y') }}</td>
    </tr>
</table>

<!-- Hissa Table -->
<table class="styled-table">
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
                        $hissaCount = 2;
                    } elseif ($hissa->gender == 'Female') {
                        $displayName .= ' (Aqiqah Female)';
                        $hissaCount = 1;
                    }
                }

                $totalHissa += $hissaCount;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $displayName }}</td>
                <td>{{ $hissaCount }}</td>
            </tr>
        @endforeach

        <tr class="green-row">
            <td colspan="2"><strong>Total Hissa</strong></td>
            <td><strong>{{ $totalHissa }}</strong></td>
        </tr>
    </tbody>
</table>

<!-- Collected By -->
<table class="styled-table">
    <tr class="green-row">
        <td colspan="3">
            <strong>Payment Collected By:</strong> {{ Auth::user()->name ?? 'N/A' }} ({{ Auth::user()->mobile ?? '' }})
        </td>
    </tr>
</table>

<!-- Footer Logo -->
@if($general->footerlogo)
    <table class="styled-table">
        <tr>
            <td colspan="3" style="text-align: center;">
                <img src="{{ asset('general/' . $general->footerlogo) }}" alt="Footer Logo" style="max-width: 100px;">
            </td>
        </tr>
    </table>
@endif

<!-- Footer Note -->
<table class="styled-table">
    @if($general->note)
        <tr class="green-row">
            <td colspan="3"><strong>Note:</strong> {{ $general->note }}</td>
        </tr>
    @endif
    @if($general->footer)
        <tr class="green-row">
            <td colspan="3">{{ $general->footer }}</td>
        </tr>
    @endif
</table>

</body>
</html>
