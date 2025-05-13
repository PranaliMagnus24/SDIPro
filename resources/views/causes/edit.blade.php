@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h1 class="text-right mb-1">Edit causes</h1>
    <a href="{{ route('causeslist') }}" class="btn btn-secondary text-right ms-2"><i class="fa fa-arrow-left"></i> Back</a>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('causes.update', $causes->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Title<span style="color: red;">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $causes->title) }}" class="form-control @error('title') is-invalid @enderror">
                    @error('title')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


                <div class="mb-3">
    <label for="content" class="form-label">Content<span style="color: red;">*</span></label>
    <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="5">{{ old('content', $causes->content ?? '') }}</textarea>
    @error('content')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<div class="mb-3">
    <label for="excerpt" class="form-label">Excerpt<span style="color: red;">*</span></label>
    <textarea name="excerpt" id="excerpt" class="form-control @error('excerpt') is-invalid @enderror" rows="5">{{ old('excerpt', $causes->excerpt ?? '') }}</textarea>
    @error('excerpt')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>


                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="amount" class="form-label">Amount<span style="color: red;">*</span></label>
                        <input type="text" name="amount" id="amount" value="{{ old('amount', $causes->amount) }}" class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="category" class="form-label">category<span style="color: red;">*</span></label>
                        <select name="category" class="form-control @error('category') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->name }}" {{ old('category', $causes->category) == $category->name ? 'selected' : '' }}>

                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>                    @error('category')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="deadline" class="form-label">Deadline<span style="color: red;">*</span></label>
                        <input type="date" name="deadline" id="deadline" value="{{ old('deadline', \Carbon\Carbon::parse($causes->deadline)->format('Y-m-d')) }}" class="form-control @error('deadline') is-invalid @enderror">

                        @error('deadline')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="metatitle" class="form-label">Meta Title</label>
                        <input type="text" name="metatitle" id="metatitle" value="{{ old('metatitle', $causes->metatitle) }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="metatag" class="form-label">Meta Tag</label>
                        <input type="text" name="metatag" id="metatag" value="{{ old('metatag', $causes->metatag) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="metadescription" class="form-label">Meta Description</label>
                        <textarea name="metadescription" id="metadescription" class="form-control" rows="3">{{ old('metadescription', $causes->metadescription) }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ogmetatitle" class="form-label">OG Meta Title</label>
                        <input type="text" name="ogmetatitle" id="ogmetatitle" value="{{ old('ogmetatitle', $causes->ogmetatitle) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="ogmetadescription" class="form-label">OG Meta Description</label>
                        <textarea name="ogmetadescription" id="ogmetadescription" class="form-control" rows="3">{{ old('ogmetadescription', $causes->ogmetadescription) }}</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ogmetaimage" class="form-label">OG Meta Image</label>
                        <input type="file" name="ogmetaimage" id="ogmetaimage" class="form-control">
                        
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="upload_image" class="form-label">Upload Image<span style="color: red;">*</span></label>
                        <input type="file" name="upload_image" id="upload_image" class="form-control @error('upload_image') is-invalid @enderror">
                        @php
                        $imagePath = $causes->upload_image;
                        $imageName = $imagePath ? basename($imagePath) : null;
                    @endphp

                    @if($causes->upload_image)
                        <div class="mt-2">
                            Current: <strong>{{ $imageName }}</strong><br>
                            <img src="{{ asset($causes->upload_image) }}" alt="Current Image" width="100">
                        </div>
                    @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="attachment" class="form-label">Attachment<span style="color: red;">*</span></label>
                        <input type="file" name="attachment" id="attachment" class="form-control @error('attachment') is-invalid @enderror">
                        @php
                        $pdfPath = $causes->attachment;
                        $pdfName = $pdfPath ? basename($pdfPath) : null;
                    @endphp

                    @if($causes->attachment)
                        <div class="mt-2">
                            Current: <strong>{{ $pdfName }}</strong><br>
                            <a href="{{ asset($causes->attachment) }}" target="_blank">Download PDF</a>
                        </div>
                    @endif

                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ old('status', $causes->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $causes->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
