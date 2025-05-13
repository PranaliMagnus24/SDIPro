@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Qurbani Details</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('qurbanis.index') }}"> Back</a>
        </div>
    </div>
</div>

<div class="row">
 
        <div class="col-md-6 mb-3">
        <div class="form-group">
            <strong>Contact Name:</strong>
            {{ $qurbani->contact_name }}
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="form-group">
            <strong>Contact Number:</strong>
            {{ $qurbani->mobile }}
        </div>
    </div>
    </div>
    


<div class="row">
    <div class="container pt-4">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Aqiqah</th>
                        <th class="text-center">Gender</th>
                        <th class="text-center">Hissa</th>
                        <th class="text-center">Amount</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @php
                        $amount = 1500; // Default amount per Hissa
                        $totalAmount = 0;
                    @endphp
                    
                    @foreach ($hisses as $hisse)
                        @php
                            // Calculate hissa based on gender
                            $hissa = ($hisse->aqiqah == 1) ? ($hisse->gender == 'Male' ? 2 : 1) : 1; // Default hissa is 1 if no Aqiqah
                            $amountForHissa = $amount * $hissa;
                            $totalAmount += $amountForHissa; // Add to total amount
                        @endphp
                        <tr class="rowClass"> 
                            <td class="row-index text-center"> 
                                {{ $hisse->name }}
                            </td>
                            <td class="row-index text-center"> 
                                @if ($hisse->aqiqah == 1)
                                    Aqiqah
                                @else
                                    -
                                @endif
                            </td>  
                            <td class="row-index text-center"> 
                                @if ($hisse->aqiqah == 1)
                                    {{ $hisse->gender }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="row-index text-center"> 
                                {{ $hissa }}
                            </td>
                            <td class="row-index text-center"> 
                                &#8377; {{ $amountForHissa }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
<div class="row">
      <div class="col-md-6 mb-3">
        <div class="form-group">
            <strong>Receipt Book:</strong>
            {{ $qurbani->receipt_book }}
        </div>
    </div>

       <div class="col-md-6 mb-3">
        <div class="form-group">
            <strong>Total Amount:</strong>
            &#8377; {{ $totalAmount }}
        </div>
    </div>
    </div>
    <div class="row">
       <div class="col-md-6 mb-3">
        <div class="form-group">
            <strong>Payment Status:</strong>
            {{ $qurbani->payment_status }}
        </div>
    </div>
    
    @if ($qurbani->payment_type == 'GPay')
           <div class="col-md-6 mb-3">
            <div class="form-group">
                <strong>Transaction Id:</strong>
                {{ $qurbani->transaction_number }}
            </div>
        </div>
    @endif
    @if ($qurbani->payment_type == 'Cash')
           <div class="col-md-6 mb-3">
            <div class="form-group">
                <strong>Paid Amount:</strong>
                &#8377; {{ $totalAmount }}
            </div>
        </div>
    @endif
</div>

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
</div>
@endsection
