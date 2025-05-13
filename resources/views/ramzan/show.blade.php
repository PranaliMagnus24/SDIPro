@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mt-1 mb-4">
        <a href="{{ route('collectionlist') }}" class="btn btn-sm btn-outline-secondary me-1">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="flex-grow-1 text-center">
            <h3 class="mb-0">Collection Details</h3>
        </div>
        <div style="width: 80px;"></div> {{-- Spacer --}}
    </div>

    <table class="table table-bordered">
        <tr>
            <td class="left-align"><strong>Name:</strong> {{ ucfirst($collection->name) }}</td>
            <td class="left-align"><strong>Date:</strong> {{ $collection->date }}</td>
        </tr>

        <tr>
            <td class="left-align"><strong>Contact:</strong> {{ $collection->contact }}</td>
            <td class="left-align"><strong>Donation Category:</strong> {{ $collection->donationcategory }}</td>
        </tr>

        <tr>
            <td class="left-align"><strong>Amount:</strong> ₹{{ $collection->amount }}</td>
            <td class="left-align"><strong>Payment Mode:</strong> {{ $collection->payment_mode }}</td>
        </tr>

        @if($collection->payment_mode == 'Online')
        <tr>
            <td colspan="2" class="left-align"><strong>Transaction ID:</strong> {{ $collection->transaction_id }}</td>
        </tr>
        @endif
    </table>
</div>
@endsection
