@extends('layouts.app')

@section('content')
<div class="row">
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

<form action="{{ route('qurbanis.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <strong>Name:<span style="color: red;">*</span></strong>
                <input type="text" name="contact_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('contact_name') }}" placeholder="Name" >
            </div>
            @error('contact_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <strong>Mobile:<span style="color: red;">*</span></strong>
                <input type="text" name="mobile" maxlength="10" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" placeholder="Mobile" >
                @error('mobile')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <strong>Receipt Book:</strong>
                    <input type="text" name="receipt_book" class="form-control @error('receipt_book') is-invalid @enderror"
                        value="{{ old('receipt_book', isset($qurbani) ? $qurbani->receipt_book : '') }}" 
                        placeholder="Enter Receipt Number (Optional)">
                    @error('receipt_book')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="container pt-4">
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
                        <tr class="rowClass">
                            <td class="text-center">
                                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0"> 
                                <input type="checkbox" class="aqiqah-check">
                            </td>
                            <td class="text-center">
                                <input type="text" name="name[]" class="form-control name-input" placeholder="Name" >
                            </td>
                            <td class="text-center">
                                <select name="gender[]" class="form-control aqiqah-select" style="display:none;">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <input type="number" name="hissa[]" class="form-control hissa-input" value="1"  readonly>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-danger remove" type="button">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-md btn-primary" id="addBtn" type="button">Add New Row</button>
        </div>

        <div class="row pt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Total Amount:</strong>
                    ₹ <span id="txtamount">0</span>
                </div>
            </div>

            <div class="col-md-6">
                <strong>Payment Method:<span style="color: red;">*</span></strong>
                <select name="payment_type" id="payment_method" class="form-control" onchange="togglePaymentDetails(this);">
    <option value="">Payment Method</option>
    <option value="Cash">Cash</option>
    <option value="RazorPay">Online</option>
</select>

                @error('payment_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Transaction ID - Show only if RazorPay selected --}}
        <div class="form-group mt-2" id="razorpay-details" style="display: none;">
            <label for="transaction_number"><strong>Transaction ID:<span style="color: red;">*</span></strong></label>
            <input type="text" name="transaction_number" id="transaction_number"
                class="form-control @error('transaction_number') is-invalid @enderror"
                placeholder="Enter Transaction ID">
            @error('transaction_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mb-3 mt-2">
                <i class="fa-solid fa-floppy-disk"></i> Submit
            </button>
        </div>
    </div>
</form>

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    let totalAmount = 0;

    function calculateTotal() {
        totalAmount = 0;

        $(".rowClass").each(function () {
            let isAqiqah = $(this).find(".aqiqah-check").is(":checked");
            let selectedGender = $(this).find(".aqiqah-select").val();
            let hissaAmount = 1500;
            let hissaCount = 1;
            let name = $(this).find(".name-input").val().trim();

            if (name !== "") {
                if (isAqiqah) {
                    hissaCount = (selectedGender === "Male") ? 2 : 1;
                }

                $(this).find(".hissa-input").val(hissaCount);
                totalAmount += hissaCount * hissaAmount;
            }
        });

        $("#txtamount").text(totalAmount.toFixed(2));
    }

    $("#addBtn").click(function () {
        let newRow = `
        <tr class="rowClass">
            <td class="text-center">
                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
                <input type="checkbox" class="aqiqah-check">
            </td>
            <td class="text-center">
                <input type="text" name="name[]" class="form-control name-input" placeholder="Name">
            </td>
            <td class="text-center">
                <select name="gender[]" class="form-control aqiqah-select" style="display:none;">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td class="text-center">
                <input type="number" name="hissa[]" class="form-control hissa-input" value="1" readonly>
            </td>
            <td class="text-center">
                <button class="btn btn-danger remove" type="button">Remove</button>
            </td>
        </tr>`;
        $('#tbody').append(newRow);
    });

    $(document).on("input", ".name-input", calculateTotal);
    $(document).on("change", ".aqiqah-select", calculateTotal);

    $(document).on("change", ".aqiqah-check", function () {
        let row = $(this).closest("tr");
        let genderSelect = row.find(".aqiqah-select");
        let hiddenAqiqahInput = row.find(".aqiqah-input");

        if ($(this).is(":checked")) {
            hiddenAqiqahInput.val("1");
            genderSelect.show();
        } else {
            hiddenAqiqahInput.val("0");
            genderSelect.hide().val("");
        }
        calculateTotal();
    });

    $(document).on("click", ".remove", function () {
        if ($(".rowClass").length > 1) {
            $(this).closest("tr").remove();
        }
        calculateTotal();
    });

    window.togglePaymentDetails = function (select) {
        $("#razorpay-details").toggle(select.value === 'RazorPay');
    };

    // Initialize visibility on page load
    togglePaymentDetails(document.getElementById('payment_method'));

    calculateTotal();
});
</script>
@endsection
