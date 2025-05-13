@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <div class="row align-items-center mb-3">
        <div class="col-md-6">
            <h1 class="mb-0">Causes List</h1>
        </div>
        <div class="col-md-6 text-md-end">
          
            <a class="btn btn-success btn-sm" href="{{ route('causes.create') }}">
                <i class="fa fa-plus"></i>
            </a>
          
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
            <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($causes as $cause)
            <tr>
            <td>{{ ++$i }}</td>
                <td>{{ $cause->title }}</td>
                <td>{{ $cause->category }}</td>
                <td>{{ $cause->amount }}</td>
                <td>{{ ucfirst($cause->status) }}</td>
                <td>
                <a href="{{ route('causes.show', $cause->id) }}" class="btn btn-sm btn-info">
    <i class="fas fa-eye"></i> 
</a>

<a href="{{ route('causes.edit', $cause->id) }}" class="btn btn-sm btn-warning">
    <i class="fas fa-edit"></i>
</a>

<form action="{{ route('causes.delete', $cause->id) }}" method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
        <i class="fas fa-trash-alt"></i> 
    </button>
</form>



                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
