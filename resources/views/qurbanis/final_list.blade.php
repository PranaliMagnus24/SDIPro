@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Qurbani Final List - Day {{ $day }}</h4>
        <a href="{{ route('pdf.finallist', $day) }}" class="btn btn-success" target="_blank">
            <i class="bi bi-download"></i> Download PDF
        </a>
    </div>

    @php
        $receiptarray = [];
        $boxCount = 0;
        $listNo = 1;
        $mobile = [];
    @endphp

    {{-- <h5 class="mb-3">List {{ $listNo }}</h5> --}}

    @for ($i = 0; $i < count($columns); $i++)
        @if($boxCount % 2 == 0)
        <div class="row mb-4">
        @endif

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold text-center">{{ ($boxCount % 200) + 1 }}</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 10%">ID</th>
                                    <th style="width: 30%">Receipt</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                        <td>
                                            @if (!in_array($qurbani->id, $mobile))
                                                @if (!empty($qurbani->mobile))
                                                    {{ $qurbani->mobile }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @php $receiptarray[] = $qurbani->id; @endphp
                                    @php $mobile[] = $qurbani->id; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @php $boxCount++; @endphp

        @if($boxCount % 2 == 0)
        </div>
        @endif

        @if($boxCount % 200 == 0 && $boxCount < count($columns))
            @php $listNo++; @endphp
            <hr>
            <h5 class="mb-3">List {{ $listNo }}</h5>
        @endif
    @endfor

    @if($boxCount % 2 != 0)
        </div>
    @endif
</div>
@endsection
