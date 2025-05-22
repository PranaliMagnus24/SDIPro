@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Qurbani Collection List</h2>
    </div>
    <div class="col-md-6 text-md-end">
        @can('qurbani-create')
        <a class="btn btn-success btn-sm" href="{{ route('qurbanis.create') }}">
            <i class="fa fa-plus"></i>
        </a>
        @endcan
    </div>
</div>

<!-- Filter Form -->
<form method="GET" action="{{ route('qurbanis.index') }}">
    <div class="row mb-4">
        <table class="table table-bordered">
            <tr>
                <td>
                    <label for="contact_name">Name:</label>
                    <input type="text" id="contact_name" name="contact_name" value="{{ request('contact_name') }}" class="form-control" placeholder="Name">
                </td>
                <td>
                    <label for="mobile">Mobile:</label>
                    <input type="number" id="mobile" name="mobile" value="{{ request('mobile') }}" class="form-control" placeholder="Mobile">
                </td>
                <td>
                    <label for="receipt_book">Receipt No:</label>
                    <input type="text" id="receipt_book" name="receipt_book" value="{{ request('receipt_book') }}" class="form-control" placeholder="Receipt Number">
                </td>
                <td>
                        <label for="collected_by">Collected By:</label>
                        <select name="collected_by" class="form-select">
                            <option value="">Collected By</option>
                            @foreach($collectedUsers as $user)
                                <option value="{{ $user->id }}" {{ request('collected_by') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
    <label for="year">Year:</label>
    <select name="year" class="form-control">
        <option value="2025" {{ request('year', 2025) == 2025 ? 'selected' : '' }}>2025</option>
        <option value="2024" {{ request('year') == 2024 ? 'selected' : '' }}>2024</option>
    </select>
</td>

                <td>
                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                    <a href="{{ url('qurbanis') }}" class="btn btn-secondary mt-4 ms-2">Reset</a>
                </td>
            </tr>
        </table>
    </div>
</form>


{{-- @session('success')
    <div class="alert alert-success" role="alert">
        {{ $value }}
    </div>
@endsession --}}

@php
    function sortLink($label, $field) {
        $currentSort = request('sort_by');
        $currentOrder = request('order', 'desc');
        $isCurrent = $currentSort == $field;
        $newOrder = ($isCurrent && $currentOrder == 'asc') ? 'desc' : 'asc';
        $icon = $isCurrent ? ($currentOrder == 'asc' ? '▲' : '▼') : '⇅';

        $query = request()->except('sort_by', 'order');
        $query['sort_by'] = $field;
        $query['order'] = $newOrder;
        $url = request()->url() . '?' . http_build_query($query);

        return "<a href='{$url}' style='color: inherit; text-decoration: none; font-weight: bold;'>{$label} <span>{$icon}</span></a>";
    }
@endphp


<table class="table table-bordered nowrap">
    <tr>
        <th>No</th>
        <th>{!! sortLink('Name', 'contact_name') !!}</th>
        <th>{!! sortLink('Mobile', 'mobile') !!}</th>
        <th>{!! sortLink('Hissa', 'hissa') !!}</th>
        <th width="280px" class="nowrap">Action</th>
    </tr>

    @foreach ($qurbanis as $qurbani)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $qurbani->contact_name }}</td>
        <td>{{ $qurbani->mobile }}
            @php
                $pdfUrl = "https://sdi.mytasks.in/generate-pdf/" . base64_encode($qurbani->id);
                $message = "Assalamualaikum\nQurbani Booking Confirmation From *SDI Branch Nasik*.\nReceipt No: " . $qurbani->id . "\nDownload your receipt PDF: " . $pdfUrl;
                $whatsappUrl = "https://api.whatsapp.com/send/?phone=91" . $qurbani->mobile . "&text=" . urlencode($message);
            @endphp
            <a href="{{ $whatsappUrl }}&amp;type=phone_number&amp;app_absent=0" target="_blank">Whatsapp</a>
        </td>

        <td>{{ $qurbani->details->sum('hissa') }}</td>
        <td>
            <form action="{{ route('qurbanis.destroy',$qurbani->id) }}" method="POST">
                <a class="btn btn-warning btn-sm" href="/generate-pdf/{{ base64_encode($qurbani->id) }}" target="_blank">
                    <i class="fa-regular fa-file-pdf"></i>
                </a>
                <a class="btn btn-info btn-sm" href="{{ route('qurbanis.show',$qurbani->id) }}">
                    <i class="fas fa-eye"></i>
                </a>
                <a class="btn btn-primary btn-sm" href="{{ route('qurbani.edit', $qurbani->id)}}"><i class="fas fa-edit"></i></a>
                @csrf
                @method('DELETE')
                @can('qurbani-delete')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                @endcan
            </form>
        </td>
    </tr>
    @endforeach


</table>
<div class="d-flex justify-content-center mt-3">
        {{ $qurbanis->links() }}
    </div>

<!-- <p class="text-center text-primary"><small>magnusIdeas.com</small></p> -->
@endsection
