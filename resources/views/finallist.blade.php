<!DOCTYPE html>
<html>
<head>
    <title>Faizane Sadique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            font-size: 10px;
        }
        .center-text {
            text-align: center;
        }
        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            text-align: left;
            border: 1px solid #dddddd;
        }
        .styled-table th, .styled-table td {
            padding: 2px 2px;
            border: 1px solid #dddddd;
        }
        .styled-table tbody tr:nth-of-type(even) {
            background-color: #f3f3f3;
        }
        .styled-table tbody tr:hover {
            background-color: #f1f1f1;
        }
        .page-break {
            page-break-after: always;
        }
        .list-heading {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
    </style>
</head>
<body>

@php
    $receiptarray = [];
    $boxCount = 0;
    $listNo = 1;
@endphp

<div class="list-heading">List {{ $listNo }}</div>

@for ($i = 0; $i < count($columns); $i++)
    @if($boxCount % 2 == 0)
        <table width="100%">
        <tr>
    @endif

    <td class="center-text" valign="top" width="50%">
        <strong>{{ ($boxCount % 200) + 1 }}</strong>
        <table class="styled-table">
            @php $no = 0; @endphp
            @foreach ($columns[$i] as $hisse)
                @php
                    $qurbani = App\Models\Qurbani::find($hisse['qurbani_id']);
                @endphp
                <tr>
                    <td>{{ ++$no }}</td>
                    <td>
                        @if (!in_array($qurbani->id, $receiptarray))
                            {{ $qurbani->id }}
                            @if (!empty($qurbani->receipt_book))
                                ({{ $qurbani->receipt_book }})
                            @endif
                        @endif
                    </td>
                    <td>{{ $hisse['name'] }}</td>
                </tr>
                @php $receiptarray[] = $qurbani->id; @endphp
            @endforeach
        </table>
    </td>

    @php $boxCount++; @endphp

    @if($boxCount % 2 == 0)
        </tr>
        </table>
    @endif

    @if($boxCount % 10 == 0)
        <div class="page-break"></div>
    @endif

    @if($boxCount % 200 == 0 && $boxCount < count($columns))
        @php $listNo++; @endphp
        <div class="page-break"></div>
        <div class="list-heading">List {{ $listNo }}</div>
    @endif
@endfor

@if($boxCount % 2 != 0)
    </tr></table>
@endif

</body>
</html>
