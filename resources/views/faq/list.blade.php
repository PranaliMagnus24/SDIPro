@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-right mb-1">Faq</h1>
        <div class="d-flex justify-content-end mb-1">
            <a href="{{ route('faq.create') }}" class="btn btn-success">+</a>
        </div>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($faqs as $faq)
                    <tr>
                    <td>{{  $faq->id }}</td>
                        <td>{{ $faq->title }}</td>
                        <td>{{ $faq->description }}</td>
                        <td>{{ $faq->status }}</td>
                        <td>
                            <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-primary btn-sm">  <i class="fas fa-edit"></i></a>
                            <form action="{{ route('faq.delete', $faq->id) }}" method="POST" class="d-inline">
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