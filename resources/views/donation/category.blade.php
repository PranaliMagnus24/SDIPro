@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-right mb-1">Create</h1>
        <a href="{{ route('categorylist') }}" class="btn btn-secondary text-right ms-2"><i class="fa fa-arrow-left"></i>Back</a>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name<span style="color: red;">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                  
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control" >
                    </div>
                
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save</button>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
