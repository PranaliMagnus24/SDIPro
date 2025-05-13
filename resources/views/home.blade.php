@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <!-- Total Ramadan Receipts -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">{{ __('Ramadan Receipts') }}</div>
                <div class="card-body">
                    {{ $ramadanReceiptCount }}
                </div>
            </div>
        </div>
        
        <!-- Cash Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">{{ __('Cash Collection') }}</div>
                <div class="card-body">
                    Rs. {{ number_format($cashAmount, 2) }} ({{ $cashReceipts }})
                </div>
            </div>
        </div>

        <!-- Online Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">{{ __('Online Collection') }}</div>
                <div class="card-body">
                    Rs. {{ number_format($onlineAmount, 2) }} ({{ $onlineReceipts }})
                </div>
            </div>
        </div>

        <!-- Unselected Payment Collection -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">{{ __('Payment Mode(Not selected)') }}</div>
                <div class="card-body">
                    Rs. {{ number_format($unselectedAmount, 2) }} ({{ $unselectedReceipts }})
                </div>
            </div>
        </div>

        <!-- Ramadan Collection (Cash + Online + Unselected) -->
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">{{ __('Ramadan Collection') }}</div>
                <div class="card-body">
                    Rs. {{ number_format($totalAmount, 2) }}
                </div>
            </div>
        </div>
    </div>

    <br>


    @if(Auth::user()->roles[0]->name === 'Admin' && $users->isNotEmpty())
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Ramadan Collection User List') }}</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>User Name</th>
                                <th>Receipts</th>
                                <th>Cash Amount </th>
                                <th>Online Amount </th>
                                <th>Payment Mode(Not selected) </th>
                                <th>Total Amount</th>
                            </tr>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->receipt_count }}</td>
                                    <td>Rs. {{ number_format($user->cash_amount, 2) }} ({{ $user->cash_receipts }})</td>
                                    <td>Rs. {{ number_format($user->online_amount, 2) }} ({{ $user->online_receipts }})</td>
                                    <td>Rs. {{ number_format($user->unselected_amount, 2) }} ({{ $user->unselected_receipts }})</td>
                                    <td><strong>Rs. {{ number_format($user->total_amount, 2) }}</strong></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection


{{--<div class="container">
     <div class="row  justify-content-center">
        <div class="col-md-4">
            <div class="card">
                    <div class="card-header">{{ __('Total Receipts') }}</div>
                    <div class="card-body">
                        {{ $receiptcount }}
                    </div>
                </div>
                </div>
                <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ __('Total Hisse') }}</div>
                    <div class="card-body">
                        {{ $qurbanihisse }}
                    </div>
                </div>
            </div>
        </div>
        <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>Team</th>
                            <th>Total Receipts</th>
                            <th>Hisse</th>
                        </tr>
                        @php
                           $totalReceipt = 0;
                           $hisseTotalcount = 0;
                       @endphp
                        @foreach ($usersWithQurbaniCount as $qurbani)
                       @php
                           $totalReceipt += $qurbani->qurbani_booked;
                           $hissecount = App\Models\QurbaniHisse::where('user_id',$qurbani->id)->count();
                           $hisseTotalcount += $hissecount;
                       @endphp
                        <tr>
                            <td>{{ $qurbani->name }}</td>
                            <td>{{ $qurbani->qurbani_booked }}</td>
                            <td>{{ $hissecount }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><strong>Total Receipts</strong></td>
                            <td>{{$totalReceipt}}</td>
                            <td>{{$hisseTotalcount}}</td>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>--}}