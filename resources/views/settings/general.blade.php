@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Update General Settings</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $general = DB::table('general')->where('ID', 1)->first();
    @endphp

    <form action="{{ route('update.general.settings') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Row 1: Logo & Favicon -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <input type="file" class="form-control" name="logo">
                @if($general->logo)
                    <img src="{{ asset('general/' . $general->logo) }}" alt="Current Logo" width="100" class="mt-2">
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Favicon</label>
                <input type="file" class="form-control" name="favicon">
                @if($general->favicon)
                    <img src="{{ asset('general/' . $general->favicon) }}" alt="Current Favicon" width="50" class="mt-2">
                @endif
            </div>
        </div>

        <!-- Row 2: Email & Address -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $general->email }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address">{{ $general->address }}</textarea>
            </div>
        </div>

        <!-- Row 3: State & City -->
        @php
            use App\Models\State;
            $maharashtra = State::where('name', 'Maharashtra')->first();
            $cities = $maharashtra ? $maharashtra->cities : collect();
        @endphp
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">State</label>
                <input type="text" class="form-control" name="state" value="Maharashtra" readonly>
                <input type="hidden" name="state_id" value="{{ $maharashtra->id }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">City</label>
                <select name="city" class="form-control" id="city">
                    @foreach($cities as $city)
                        <option value="{{ $city->name }}" {{ $general->city == $city->name ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
                <label class="form-label">Heading</label>
                <input type="text" class="form-control" name="heading" value="{{ $general->heading }}">
            </div>
        <!-- Row 4: Title & Subtitle -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="{{ $general->title }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Subtitle</label>
                <input type="text" class="form-control" name="subtitle" value="{{ $general->subtitle }}">
            </div>
        </div>

        <!-- Row 5: Contact & Bank Detail -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Contact</label>
                <input type="text" class="form-control" name="contact" value="{{ $general->contact }}">
            </div>
            <div class="col-md-6">
    <label class="form-label">Bank Detail</label>
    <textarea class="form-control" name="bankdetail">{{ $general->bankdetail }}</textarea>
</div>

        </div>

        <!-- Row 6: Upload QR Code & Footer Logo -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Upload QR Code</label>
                <input type="file" class="form-control" name="uploadqrcode">
                @if($general->uploadqrcode)
                    <img src="{{ asset('general/' . $general->uploadqrcode) }}" alt="QR Code" width="100" class="mt-2">
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Footer Logo</label>
                <input type="file" class="form-control" name="footerlogo">
                @if($general->footerlogo)
                    <img src="{{ asset('general/' . $general->footerlogo) }}" alt="Footer Logo" width="100" class="mt-2">
                @endif
            </div>
        </div>

        <!-- Row 7: Link & Note -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Link</label>
                <input type="text" class="form-control" name="link" value="{{ $general->link }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Note</label>
                <input type="text" class="form-control" name="note" value="{{ $general->note }}">
            </div>
        </div>

        <!-- Row 8: Footer & Trust Register Number -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Footer</label>
                <input type="text" class="form-control" name="footer" value="{{ $general->footer }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Trust Register Number</label>
                <input type="text" class="form-control" name="trust_register_number" value="{{ $general->trust_register_number }}">
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stateId = document.querySelector('input[name="state_id"]').value;
        const savedCity = "{{ $general->city }}"; // Get the saved city from backend

        fetch(`/get-cities/${stateId}`)
            .then(res => res.json())
            .then(data => {
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '';

                Object.entries(data).forEach(([id, name]) => {
                    const option = new Option(name, name);
                    if (name === savedCity) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
            });
    });
</script>
@endsection
