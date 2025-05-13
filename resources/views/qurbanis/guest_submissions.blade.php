@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Guest Qurbani List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Contact Name</th>
                <th>Mobile</th>
                <th>Hisse</th>
                <th>Transaction ID</th>
                <th>Payment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($qurbanis as $qurbani)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $qurbani->contact_name }}</td>
                <td>{{ $qurbani->mobile }}</td>
                <td>{{ $qurbani->hissas->count() }}</td>
                <td>{{ $qurbani->transaction_number }}</td>
                <td>{{ $qurbani->payment_type }}</td>
               
                <td>
                    <a href="{{ route('qurbanis.show', $qurbani->id) }}" class="btn btn-info btn-sm">View</a>
                    <form action="{{ route('qurbani.approve', $qurbani->id) }}" method="POST" style="display: inline-block;">
        @csrf
        <button type="submit" class="btn btn-success btn-sm">Approve</button>
        </form>


                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
