@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <h1 class="text-right mb-1">Create</h1>
        <a href="{{ route('faqlist') }}" class="btn btn-secondary text-right ms-2"><i class="fa fa-arrow-left"></i>Back</a>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('faq.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
    <label for="title" class="form-label">Title<span style="color: red;">*</span></label>
    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}">

    @error('title')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" name="description" id="description" class="form-control" >
                    </div>
                    <div class="mb-3">
    <label for="status" class="form-label">Status<span style="color: red;">*</span></label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
        <option value="">-- Select Status --</option>
        <option value="active" {{ old('status', $faq->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $faq->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    @error('status')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Save</button>
                       
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection
