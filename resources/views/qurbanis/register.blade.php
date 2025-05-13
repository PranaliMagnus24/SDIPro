<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Qurbani Form - Sunni Dawate Islami</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      background-color: #e8f5e9;
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
    }
    .form-wrapper {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
      padding: 0;
      overflow: hidden;
    }
    .form-header {
      background-color: rgb(76, 158, 65);
      color: #fff;
      padding: 30px 20px;
      text-align: center;
    }
    .form-header img {
      max-height: 70px;
      margin-bottom: 15px;
    }
    .form-header h4 {
      margin: 10px 0 5px;
      font-size: 1.3rem;
    }
    .form-header p {
      margin: 0;
      font-size: 0.95rem;
    }
    .form-header small {
      display: block;
      margin-top: 4px;
      font-size: 0.85rem;
    }
    .form-body {
      padding: 30px;
    }
    h3 {
      font-weight: 500;
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }
    .green-row {
      background-color: #d0f0c0;
      color: rgb(33, 206, 45);
      padding: 10px;
      border-radius: 6px;
    }
    .form-group strong, label strong {
      font-weight: 500;
      font-size: 1rem;
    }
    input.form-control, select.form-control {
      border: 1px solid #ccc;
      border-radius: 10px;
      padding: 10px 15px;
      font-size: 1rem;
      transition: border-color 0.3s;
    }
    input.form-control:focus, select.form-control:focus {
      border-color: rgb(76, 158, 65);
      box-shadow: 0 0 0 2px rgb(76, 158, 65);
    }
    .btn-primary {
      background-color: rgb(76, 158, 65);
      border-color: rgb(76, 158, 65);
      border-radius: 8px;
      padding: 10px 24px;
    }
    .btn-primary:hover {
      background-color: rgb(76, 158, 65);
    }
    .btn-danger {
      border-radius: 8px;
      padding: 6px 14px;
    }
    .table th, .table td {
      vertical-align: middle !important;
    }
    #addBtn {
      margin-top: 10px;
      border-radius: 8px;
    }
    .text-danger {
      font-size: 0.875rem;
    }
    @media screen and (max-width: 576px) {
      h3 {
        font-size: 1.4rem;
      }
      .form-body {
        padding: 20px;
      }
      input.form-control, select.form-control {
        font-size: 0.95rem;
      }
      .footer {
        background-color: rgb(76, 158, 65);
        color: white;
        padding: 20px;
        text-align: center;
      }
    }
  </style>
</head>
<body>
<div class="form-wrapper">
  <!-- Header -->
  <div class="form-header">
    <img src="{{ asset('general/' . ($general->logo ?? 'logourdu.png')) }}" alt="Logo" class="img-fluid">
    <h4 style="color:rgb(241, 243, 241);">{{ $general->title ?? '' }}</h4>
    <p style="color:rgb(238, 243, 238);">{{ $general->subtitle ?? '' }}</p>
    <small>{{ $general->address ?? '' }}</small>
    <small>Mob: {{ $general->contact ?? '' }}</small>
  </div>

  <div class="form-body">
    <div class="text-center mb-4">
      <h3 style="color:rgb(11, 12, 11);">Qurbani Form</h3>
    </div>

    <form action="{{ route('formqurbani.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <!-- Basic Info -->
      <div class="mb-3">
        <label for="contact_name"><strong>Name:<span class="text-danger">*</span></strong></label>
        <input type="text" id="contact_name" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" 
               value="{{ old('contact_name') }}" placeholder="Your full name">
        @error('contact_name') <span class="text-danger">{{ $message }}</span> @enderror
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label for="mobile"><strong>Mobile:<span class="text-danger">*</span></strong></label>
          <input type="text" id="mobile" name="mobile" maxlength="10" value="{{ old('mobile') }}" 
                 class="form-control @error('mobile') is-invalid @enderror" placeholder="10-digit mobile number">
          @error('mobile') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="col-md-6">
          <label for="receipt_book"><strong>Receipt Book:</strong></label>
          <input type="text" id="receipt_book" name="receipt_book" class="form-control @error('receipt_book') is-invalid @enderror"
                 value="{{ old('receipt_book', isset($qurbani) ? $qurbani->receipt_book : '') }}" 
                 placeholder="Enter Receipt Number (Optional)">
          @error('receipt_book') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Row for Aqiqah, Names, etc. -->
      <div class="mt-4 table-responsive">
        <table class="table table-bordered align-middle text-center">
          <thead>
            <tr>
              <th>Aqiqah</th>
              <th>Name</th>
              <th>Gender</th>
              <th>Hissa</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody id="tbody">
            <tr class="rowClass">
              <td>
                <input type="hidden" class="aqiqah-input" name="aqiqah[]" value="0">
                <input type="checkbox" class="aqiqah-check">
              </td>
              <td>
                <input type="text" name="name[]" class="form-control name-input" placeholder="Name">
              </td>
              <td>
                <select name="gender[]" class="form-control aqiqah-select d-none">
                  <option value="">Select</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
              </td>
              <td>
                <input type="number" name="hissa[]" class="form-control hissa-input" value="1" readonly>
              </td>
              <td>
                <button class="btn btn-danger remove" type="button">Remove</button>
              </td>
            </tr>
          </tbody>
        </table>
        <button class="btn btn-primary btn-sm" id="addBtn" type="button">+ Add Row</button>
      </div>

      <!-- Total and Payment Method -->
      <div class="row mt-4">
        <div class="col-md-6">
          <strong>Total Amount:</strong><br>
          ₹ <span id="txtamount">0.00</span>
        </div>
        <div class="col-md-6">
          <label for="payment_method"><strong>Payment Method:<span class="text-danger">*</span></strong></label>
          <select name="payment_type" id="payment_method" class="form-control" onchange="togglePaymentDetails(this);">
            <option value="">Select Payment Method</option>
           
            <option value="RazorPay">Online (RazorPay)</option>
          </select>
          @error('payment_type') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Online Payment Details: Two Tier Layout in a Green Container -->
<div id="online-payment-details" style="display:none; margin-top:20px; border: 2px solid #4c9e41; background-color: #d0f0c0; padding: 15px; border-radius: 8px;">
  <!-- First Row: QR Code on Left, Bank Details on Right -->
  <div class="row">
    <div class="col-md-6 text-center">
      <h5>Scan QR Code</h5>
      <img src="{{ asset('qrcode.jpg') }}" alt="QR Code" style="max-width:200px;">
    </div>
    <div class="col-md-6">
      <h5>Bank Details</h5>
      @if($general->bankdetail)
        <strong>Bank Details:</strong><br>
        <small>{!! nl2br(e($general->bankdetail)) !!}</small>
      @endif
    </div>
  </div>
  <!-- Second Row: Transaction ID on Left, Upload Payment Proof on Right -->
  <div class="row mt-3">
    <div class="col-md-6">
      <div class="form-group" id="razorpay-details">
        <label for="transaction_number"><strong>Transaction ID:<span class="text-danger">*</span></strong></label>
        <input type="text" name="transaction_number" id="transaction_number" class="form-control @error('transaction_number') is-invalid @enderror" placeholder="Enter Transaction ID">
        @error('transaction_number') <span class="text-danger">{{ $message }}</span> @enderror
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group" id="upload-payment-field">
        <label for="upload_payment"><strong>Upload Payment Proof (optional):</strong></label>
        <input type="file" name="upload_payment" id="upload_payment" class="form-control @error('upload_payment') is-invalid @enderror">
        @error('upload_payment') <span class="text-danger">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>
</div>


      <!-- Submit Button -->
      <div class="text-center mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> Submit
        </button>
      </div>
    </form>
  </div> <!-- End of form-body and form-wrapper -->

  <!-- Footer -->
  <div class="footer">
    <div class="w-100 p-3 text-center" style="background-color: rgb(76, 158, 65); color: white;">
      @if($general->note)
        <p class="mb-2"><strong>Note:</strong> {{ $general->note }}</p>
      @endif
      <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
        <img src="{{ asset('general/' . ($general->footerlogo ?? 'logourdu.png')) }}" alt="Footer Logo" class="img-fluid" style="max-height: 60px;">
        @if($general->footer)
          <p class="mb-0">{{ $general->footer }}</p>
        @endif
      </div>
    </div>
  </div>
</div>

<footer class="mt-1 pt-1 pb-1" style="color: black; text-align: center;">
  <p class="mt-1">
    &#xA9; <?= date("Y") ?> All Rights Reserved by Sunni Dawate Islami (SDI).<br>
    Developed By <a href="https://magnusideas.com" target="_blank" style="color:rgb(8, 58, 122); text-decoration: none;">Magnus Ideas Pvt. Ltd.</a>
  </p>
</footer>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
  let hissaAmount = 1500;
  
  function calculateTotal() {
    let total = 0;
    $(".rowClass").each(function () {
      let $row = $(this);
      let isAqiqah = $row.find(".aqiqah-check").is(":checked");
      let gender = $row.find(".aqiqah-select").val();
      let name = $row.find(".name-input").val().trim();
      let hissaCount = 1;
      
      if (name !== "") {
        if (isAqiqah) {
          hissaCount = (gender === "Male") ? 2 : 1;
          $row.find(".aqiqah-input").val(1);
          $row.find(".aqiqah-select").removeClass("d-none");
        } else {
          $row.find(".aqiqah-input").val(0);
          $row.find(".aqiqah-select").addClass("d-none").val('');
        }
        $row.find(".hissa-input").val(hissaCount);
        total += hissaCount * hissaAmount;
      }
    });
    $("#txtamount").text(total.toFixed(2));
  }
  
  // Add new row
  $("#addBtn").click(function () {
    let newRow = $(".rowClass:first").clone();
    newRow.find("input, select").val("");
    newRow.find(".aqiqah-check").prop("checked", false);
    newRow.find(".aqiqah-input").val(0);
    newRow.find(".aqiqah-select").addClass("d-none");
    newRow.find(".hissa-input").val(1);
    $("#tbody").append(newRow);
  });
  
  // Remove row
  $(document).on("click", ".remove", function () {
    if ($(".rowClass").length > 1) {
      $(this).closest("tr").remove();
      calculateTotal();
    }
  });
  
  // Event listeners for recalculating total
  $(document).on("input change", ".aqiqah-check, .aqiqah-select, .name-input", function () {
    calculateTotal();
  });
  
  // Toggle Online Payment Details
  window.togglePaymentDetails = function(select) {
    if (select.value === 'RazorPay') {
      $("#online-payment-details").show();
    } else {
      $("#online-payment-details").hide();
    }
  };
  
  // Initial calculation
  calculateTotal();
});
</script>
</body>
</html>
