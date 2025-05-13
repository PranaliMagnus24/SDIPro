@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex align-items-center justify-content-between mt-1 mb-4">
        <a href="{{ route('causeslist') }}" class="btn btn-sm btn-outline-secondary me-1">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="flex-grow-1 text-center">
            <h3 class="mb-0">Causes Details</h3>
        </div>
        <div style="width: 80px;"></div> {{-- Spacer --}}
    </div>

    <table class="table table-bordered">
        <tr>
            <td><strong>Title:</strong></td>
            <td>{{ $causes->title }}</td>
        </tr>
        <tr>
            <td><strong>Content:</strong></td>
            <td>{{ $causes->content }}</td>
        </tr>
        <tr>
            <td><strong>Excerpt:</strong></td>
            <td>{{ $causes->excerpt }}</td>
        </tr>
        <tr>
            <td><strong>Amount:</strong></td>
            <td>₹{{ $causes->amount }}</td>
        </tr>
        <tr>
            <td><strong>Category:</strong></td>
            <td>{{ $causes->category }}</td>
        </tr>
        <tr>
            <td><strong>Deadline:</strong></td>
            <td>{{ $causes->deadline }}</td>
        </tr>

        <!-- Meta Info -->
        <tr>
            <td><strong>Meta Title:</strong></td>
            <td>{{ $causes->metatitle }}</td>
        </tr>
        <tr>
            <td><strong>Meta Tag:</strong></td>
            <td>{{ $causes->metatag }}</td>
        </tr>
        <tr>
            <td><strong>Meta Description:</strong></td>
            <td>{{ $causes->metadescription }}</td>
        </tr>

        <!-- OG Meta -->
        <tr>
            <td><strong>OG Meta Title:</strong></td>
            <td>{{ $causes->ogmetatitle }}</td>
        </tr>
        <tr>
            <td><strong>OG Meta Description:</strong></td>
            <td>{{ $causes->ogmetadescription }}</td>
        </tr>
        <tr>
            <td><strong>OG Meta Image:</strong></td>
            <td>
                @if($causes->ogmetaimage)
                <img src="{{ asset($causes->ogmetaimage) }}" width="100" alt="Cause Image">
                @endif
            </td>
        </tr>

        <!-- Upload Image -->
        <tr>
            <td><strong>Upload Image:</strong></td>
            <td>
                @if($causes->upload_image)
                <img src="{{ asset($causes->upload_image) }}" width="100" alt="Cause Image">
                @endif
            </td>
        </tr>

        <!-- Attachment -->
        <tr>
            <td><strong>Attachment:</strong></td>
            <td>
                @if($causes->attachment)
                <a href="{{ asset($causes->attachment) }}" target="_blank">Download PDF</a>
                @endif
            </td>
        </tr>

        <tr>
            <td><strong>Status:</strong></td>
            <td>{{ ucfirst($causes->status) }}</td>
        </tr>
    </table>
</div>
@endsection
