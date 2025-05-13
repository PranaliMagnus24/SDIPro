@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-right mb-1">Category</h1>
        <div class="d-flex justify-content-end mb-1">
            <a href="{{ route('category.create') }}" class="btn btn-success">+</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                    <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                       
                        <td>
                            <a href="{{ route('category.edit', $category->id) }}" class="btn btn-primary btn-sm">  <i class="fas fa-edit"></i></a>
                            <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"> <i class="fas fa-trash"></i> </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endsection