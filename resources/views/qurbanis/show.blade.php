@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Qurbani Details</h2>
        <a class="btn btn-primary" href="{{ route('qurbanis.index') }}">Back</a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Contact Name</th>
                        <td>{{ $qurbani->contact_name }}</td>
                        <th>Mobile Number</th>
                        <td>{{ $qurbani->mobile }}</td>
                    </tr>
                    <tr>
                        @if(!empty($qurbani->alternative_mobile))
                        <th>Alternative Number</th>
                        <td>{{ $qurbani->alternative_mobile }}</td>
                        @endif
                        @if(!empty($qurbani->receipt_book))
                        <th>Receipt Book No.</th>
                        <td>{{ $qurbani->receipt_book }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th>Qurbani Day</th>
                        <td>{{ $qurbani->qurbani_days }}</td>
                        <th>Payment Status</th>
                        <td>{{ $qurbani->payment_status }}</td>
                    </tr>
                    @if ($qurbani->payment_type == 'Online')
                    <tr>
                        <th>Transaction Id</th>
                        <td>{{ $qurbani->transaction_number }}</td>
                        <th>Attachment</th>
                        <td>
                            @if(!empty($qurbani->upload_payment))
                            @php
                            $filePath = public_path($qurbani->upload_payment);
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                            @endphp
                            @if(file_exists($filePath))
                            @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']))
                            <img src="{{ asset($qurbani->upload_payment) }}" style="height:100px; width:100px;" alt="Uploaded Image">
                            @elseif(strtolower($fileExtension) === 'pdf')
                            <a href="{{ asset($qurbani->upload_payment) }}" target="_blank">
                                View PDF Document
                            </a>
                            @else
                            <span class="text-danger">Unsupported file type: {{ $fileExtension }}</span>
                            @endif
                            @else
                            <span class="text-danger">File not found.</span>
                            @endif
                            @else
                            <span class="text-muted">No attachment uploaded.</span>
                            @endif
                        </td>
                    </tr>
                    @elseif ($qurbani->payment_type == 'Cash')
                    <tr>
                        <th>Payment Type</th>
                        <td>Cash</td>
                        <th>Paid Amount</th>
                        <td>&#8377; {{ $qurbani->total_amount }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Hissa Table --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Hissa</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalHissa = 0; @endphp
                    @foreach($hisses as $index => $hissa)
                    @php
                        $hissaCount = 1;
                        $displayName = $hissa->name;
                        if ($hissa->aqiqah == 1) {
                            $displayName .= $hissa->gender == 'Male' ? ' (Aqiqah Male)' : ' (Aqiqah Female)';
                        }
                        $totalHissa += $hissaCount;
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $displayName }}</td>
                        <td>{{ $hissaCount }}</td>
                    </tr>
                    @endforeach
                    <tr class="table-secondary">
                        <td colspan="2"><strong>Total Hissa</strong></td>
                        <td><strong>{{ $totalHissa }}</strong></td>
                    </tr>
                    <tr class="table-secondary">
                        <td colspan="2"><strong>Total Amount</strong></td>
                        <td><strong>{{ $qurbani->total_amount }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Approval Section --}}
@if (is_null($qurbani->user_id) && !$qurbani->is_approved)
    <div class="row mt-4">
        <div class="col-md-12 text-center">
            <form action="{{ route('qurbani.approve', $qurbani->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this entry?');">
                @csrf
                <button type="submit" class="btn btn-success">Approve Qurbani</button>
            </form>
        </div>
    </div>
@endif
@endsection
