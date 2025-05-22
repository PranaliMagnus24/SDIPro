@extends('layouts.app')
@section('content')
<div class="row mb-3">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Add New Qurbani</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm" href="{{ route('qurbanis.index') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<!-- Full Screen Loader (Center Positioned) -->
<div id="formLoader" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white" style="opacity: 0.7; z-index: 9999;">
    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<form action="{{ route('qurbanis.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
    @csrf
    <div class="row mb-3">
        <div class="col-md-6">
            <label for=""><strong>Name: <span style="color: red;">*</span></strong></label>
                <input type="text" name="contact_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('contact_name') }}" placeholder="Name">
            @error('contact_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>
            <div class="col-md-3">
                <label for=""><strong>Mobile: <span style="color: red;">*</span></strong></label>
                <input type="text" name="mobile" maxlength="10" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" placeholder="Mobile" >
                @error('mobile')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-3">
                <label for=""><strong>Alternative Mobile</strong></label>
                <input type="text" name="alternative_mobile" maxlength="10" value="{{ old('alternative_mobile') }}" class="form-control @error('alternative_mobile') is-invalid @enderror" placeholder="Alternative Mobile" >
                @error('alternative_mobile')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for=""><strong>Receipt Book:</strong></label>
            <input type="text" name="receipt_book" class="form-control @error('receipt_book') is-invalid @enderror" value="{{ old('receipt_book', isset($qurbani) ? $qurbani->receipt_book : '') }}" placeholder="Enter Receipt Number (Optional)">
            @error('receipt_book')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for=""><strong>Select Day</strong></label>
            <select name="qurbani_days" id="" class="form-select">
                <option value="">--Select Day--</option>
                <option value="Day 1 {{ old('qurbani_days') == 'Day 1' ? 'selected' : '' }}">Day 1</option>
                <option value="Day 2 {{ old('qurbani_days') == 'Day 2' ? 'selected' : '' }}">Day 2</option>
            </select>
        </div>
    </div>
    <div class="container mb-3">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Aqiqah</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Gender</th>
                        <th class="text-center">Hissa</th>
                        <th class="text-center">Remove</th>
                    </tr>
                </thead>
                <tbody id="tbody">
                    @if (old('name'))
                    @foreach (old('name') as $index => $name)
                        @php
                            $isHuzur = old('huzur')[$index] ?? null;
                        @endphp
                        @if ($isHuzur)
                        <tr class="rowClass huzur-row">
                            <td class="text-center">
                                <input type="hidden" name="aqiqah[]" value="">
                            </td>
                            <td class="text-center" style="width: 448px;">
                                <input type="text" name="name" class="form-control" value="HAZRAT MOHAMMAD SALLALLAHU ALAIHI WASALLAM" readonly>
                            </td>
                            <td class="text-center">
                                <select name="gender[]" class="form-select" style="display:none;">
                                    <option value="">Select</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="number" name="hissa[]" class="form-control" value="1" readonly>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="huzur[]" value="1">
                                <button class="btn btn-danger remove" type="button">Remove</button>
                            </td>
                        </tr>
                        @else
                        <tr class="rowClass">
                            <td class="text-center">
                                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="{{ old('aqiqah')[$index] ?? 0 }}">
                                <input type="checkbox" class="aqiqah-check" {{ (old('aqiqah')[$index] ?? 0) == 1 ? 'checked' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="text" name="name[]" class="form-control name-input" value="{{ $name }}" placeholder="Name">
                            </td>
                            <td class="text-center">
                                <select name="gender[]" class="form-select aqiqah-select" style="{{ (old('aqiqah')[$index] ?? 0) == 1 ? '' : 'display:none;' }}">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender')[$index] == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender')[$index] == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="number" name="hissa[]" class="form-control hissa-input" value="{{ old('hissa')[$index] ?? 1 }}" readonly>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="huzur[]" value="0">
                                <button class="btn btn-danger remove" type="button">Remove</button>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                @else
                {{-- default row --}}
                <tr class="rowClass">
                    <td class="text-center">
                        <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
                        <input type="checkbox" class="aqiqah-check">
                    </td>
                    <td class="text-center">
                        <input type="text" name="name[]" class="form-control name-input" placeholder="Name">
                    </td>
                    <td class="text-center">
                        <select name="gender[]" class="form-select aqiqah-select" style="display:none;">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <input type="number" name="hissa[]" class="form-control hissa-input" value="1" readonly>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="huzur[]" value="0">
                        <button class="btn btn-danger remove" type="button">Remove</button>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <button class="btn btn-md btn-primary" id="addBtn" type="button">Add New Row</button>
    <button class="btn btn-md btn-primary" id="addBtnHuzur" type="button">Add Huzur Name</button>
</div>
<div class="row mb-3">
    <div class="col-md-2">
        <label for="" class="form-lable"><strong>Total Amount</strong></label>
        <input type="text" class="form-control" name="total_amount" id="txtamount" readonly>
    </div>
    <div class="col-md-2">
        <strong>Payment Method:<span style="color: red;">*</span></strong>
        <select name="payment_type" id="payment_method" class="form-select" onchange="togglePaymentDetails(this);">
            <option value="">Payment Method</option>
            <option value="Cash" {{ old('payment_type') == 'Cash' ? 'selected' : '' }}>Cash</option>
            <option value="RazorPay" {{ old('payment_type') == 'Online' ? 'selected' : '' }}>Online</option>
        </select>
        @error('payment_type')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3" id="razorpay-details" style="display: none;">
        <label for="transaction_number"><strong>Transaction ID:<span style="color: red;">*</span></strong></label>
        <input type="text" name="transaction_number" id="transaction_number" class="form-control @error('transaction_number') is-invalid @enderror" placeholder="Enter Transaction ID">
        @error('transaction_number')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-3 attachement" style="display: none;" id="attachement">
        <label for="" class="form-label"><strong>Upload attachement</strong></label>
        <input type="file" class="form-control" name="upload_payment">
    </div>
</div>
<div class="col-md-12 text-center">
    <button type="submit" class="btn btn-success btn-sm" id="saveButton">
        <span id="buttonText"><i class="fa-solid fa-floppy-disk"></i> Submit</span>
        <span id="buttonLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
    </button>
</div>
</div>
</form>
@endsection
{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    const autosuggestUrl = '{{ route('qurbani.autosuggest')}}';

    ////Loader logic
function disableSubmitButton() {
        var saveButton = document.getElementById("saveButton");
        var formLoader = document.getElementById("formLoader");
        if (saveButton.disabled) {
            return false;
        }
        saveButton.disabled = true;
        formLoader.classList.remove("d-none");
        return true;
    }
</script>

