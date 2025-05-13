@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 margin-tb">
    <div class="pull-left">
      <h2>Edit Collection</h2>
    </div>
    <div class="pull-right">
      <a class="btn btn-primary btn-sm" href="{{ route('collectionlist') }}">
        <i class="fa fa-arrow-left"></i> Back
      </a>
    </div>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success text-center">
    {{ session('success') }}
  </div>
@endif

<!-- Full Screen Loader (Center Positioned) -->
<div id="formLoader" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center bg-white" 
     style="opacity: 0.7; z-index: 9999;">
  <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

<!-- onsubmit calls disableSubmit() to prevent multiple submissions -->
<form action="{{ route('collection.update', $collection->id) }}" method="POST" onsubmit="return disableSubmit();">
  @csrf
  @method('PUT')
  <input type="hidden" name="user_id" value="{{ auth()->id() }}">
  
  <div class="row">
    <!-- Name Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Name:<span style="color: red;">*</span></strong>
        <input type="text" name="name" 
               class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $collection->name) }}" placeholder="Name">
        @error('name')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
    </div>
    
    <!-- Contact Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Contact:<span style="color: red;">*</span></strong>
        <input type="text" name="contact" 
               class="form-control @error('contact') is-invalid @enderror" 
               value="{{ old('contact', $collection->contact) }}" placeholder="Contact">
        @error('contact')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
    </div>
    
    <!-- Donation Category Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Donation Category:<span style="color: red;">*</span></strong>
        <select name="donationcategory" class="form-control @error('donationcategory') is-invalid @enderror">
          <option value="">Select Category</option>
          @foreach($categories as $category)
            <option value="{{ $category->name }}" 
              {{ old('donationcategory', $collection->donationcategory) == $category->name ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('donationcategory')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
    </div>
    
    <!-- Amount Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Amount:<span style="color: red;">*</span></strong>
        <input type="text" name="amount" 
               class="form-control @error('amount') is-invalid @enderror" 
               value="{{ old('amount', $collection->amount) }}" placeholder="Amount">
        @error('amount')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>
    </div>
    
    <!-- Payment Mode Field -->
    <div class="col-md-6">
      <strong>Payment:<span style="color: red;">*</span></strong>
      <select name="payment_mode" id="payment_mode" 
              class="form-control @error('payment_mode') is-invalid @enderror" 
              onchange="togglePaymentDetails(this);">
        <option value="">Payment Method</option>
        <option value="Cash" {{ old('payment_mode', $collection->payment_mode) == 'Cash' ? 'selected' : '' }}>Cash</option>
        <option value="Online" {{ old('payment_mode', $collection->payment_mode) == 'Online' ? 'selected' : '' }}>Online</option>
      </select>
      @error('payment_mode')
        <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>
    
    <!-- Transaction ID Field (Initially Hidden) -->
    <div class="col-md-6" id="transactionField" style="display: none;">
      <strong>Transaction ID:<span style="color: red;">*</span></strong>
      <input type="text" name="transaction_id" id="transaction_id" 
             class="form-control @error('transaction_id') is-invalid @enderror" 
             value="{{ old('transaction_id', $collection->transaction_id) }}" 
             placeholder="Enter Transaction ID">
      @error('transaction_id')
        <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>
    
    <!-- Date Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Date:</strong>
        <input type="datetime-local" name="date" class="form-control" id="date" 
               value="{{ old('date', \Carbon\Carbon::parse($collection->date)->format('Y-m-d\TH:i')) }}">
      </div>
    </div>
    
    <!-- Address Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Address:</strong>
        <input type="text" name="address" 
               class="form-control" value="{{ old('address', $collection->address) }}" placeholder="Address">
      </div>
    </div>
    
    <!-- Receipt Book Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Receipt Book:</strong>
        <input type="text" name="receipt_book" id="receipt_book" 
               class="form-control" value="{{ old('receipt_book', $collection->receipt_book) }}" 
               placeholder="Enter Receipt Number (Optional)">
      </div>
    </div>
    
    <!-- Note Field -->
    <div class="col-md-6 mb-3">
      <div class="form-group">
        <strong>Note:</strong>
        <input type="text" name="note" class="form-control" 
               value="{{ old('note', $collection->note) }}" placeholder="Note">
      </div>
    </div>
    
    <!-- Submit Button with Loader Handling -->
    <div class="col-md-12 text-center mt-3">
      <button type="submit" class="btn btn-success btn-sm" id="saveButton">
        <span id="buttonText"><i class="fa-solid fa-floppy-disk"></i> Update</span>
        <span id="buttonLoader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</form>

<script>
  // Set default date if not provided
  window.onload = function() {
    const dateInput = document.getElementById("date");
    if (dateInput && !dateInput.value) {
      const currentDate = new Date();
      let year = currentDate.getFullYear();
      let month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
      let day = currentDate.getDate().toString().padStart(2, '0');
      let hours = currentDate.getHours().toString().padStart(2, '0');
      let minutes = currentDate.getMinutes().toString().padStart(2, '0');
      dateInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    // Set transaction field visibility based on payment mode on page load
    togglePaymentDetails(document.getElementById("payment_mode"));
  };

  // Show/hide transaction field based on payment mode
  function togglePaymentDetails(select) {
    let transactionField = document.getElementById("transactionField");
    let transactionInput = document.getElementById("transaction_id");
    if (select.value === "Online") {
      transactionField.style.display = "block";
      transactionInput.setAttribute("required", "required");
    } else {
      transactionField.style.display = "none";
      transactionInput.removeAttribute("required");
    }
  }

  // Disable the submit button and show the loader to prevent multiple submissions
  function disableSubmit() {
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

@endsection
