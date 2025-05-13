@extends('layouts.app')
<style>
    .pagination{
        --bs-pagination-font-size: 2rem;
    }
</style>
@section('content')

<div class="row mb-3">
    <div class="col-md-6">
        <h2>Forms List</h2>
    </div>
    <div class="col-md-6 text-md-end">
        <a class="btn btn-success btn-sm" href="{{ route('create.form') }}">
            <i class="fa fa-plus"></i> 
        </a>
    </div>
</div>
<!-- Filter Form -->
<form method="GET" action="{{ route('formlist') }}">
    <div class="row mb-4">
        <table class="table table-bordered">
            <tr>
                <td>
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="{{ request('name') }}" class="form-control">
                </td>
                <td>
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" value="{{ request('contact') }}" class="form-control">
                </td>
                <td>
                    <label for="city">City:</label>
                    <select id="city" name="city" class="form-control">
                        <option value="">Select City</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td class="align-middle">
                    <button type="submit"  class="btn btn-primary mt-4"></i> Filter
                    </button>
                </td>
            </tr>
        </table>
    </div>
</form>



@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Email</th>
            <th>Contact</th>
            <th>City</th>
            <th>Note</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($forms as $form)
            <tr>
                <td>{{ $form->id }}</td>
                <td>{{ $form->name }}</td>
                <td>{{ $form->age }}</td>
                <td>{{ $form->gender }}</td>
                <td>{{ $form->email }}</td>
                <td>{{ $form->contact }}</td>
                <td>{{ $form->cities->name }}</td>
                <td>{{ $form->note }}</td>
                <td>
                    <a href="{{ route('form.edit', $form->id) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('form.destroy', $form->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-center mt-3">
    {{ $forms->links() }}
</div>

@endsection
