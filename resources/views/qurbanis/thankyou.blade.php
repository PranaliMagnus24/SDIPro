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
    .btn-primary {
      background-color: rgb(76, 158, 65);
      border-color: rgb(76, 158, 65);
      border-radius: 8px;
      padding: 10px 24px;
    }
    .btn-primary:hover {
      background-color: rgb(76, 158, 65);
    }
    .footer {
      background-color: rgb(76, 158, 65);
      color: white;
      padding: 20px;
      text-align: center;
    }
    .footer-note {
      font-size: 0.875rem;
    }
    @media screen and (max-width: 576px) {
      h3 {
        font-size: 1.4rem;
      }
      .form-body {
        padding: 20px;
      }
      .footer {
        padding: 15px;
      }
    }
  </style>
</head>
<body>

<div class="form-wrapper">
  <!-- Header -->
  <div class="form-header">
    <img src="{{ asset('general/' . ($general->logo ?? 'logourdu.png')) }}" alt="Sunni Dawate Islami Logo" class="img-fluid">
    <h4>{{ $general->title ?? '' }}</h4>
    <p>{{ $general->subtitle ?? '' }}</p>
    <small>{{ $general->address ?? '' }}</small>
    <small>Mob: {{ $general->contact ?? '' }}</small>
  </div>

  <!-- Body -->
  <div class="form-body">
    <div class="text-center mb-4">
      <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
      <h3>Thank You for Your Qurbani Registration!</h3>
      <a href="{{ route('create.qurbani') }}" class="btn btn-primary mt-3">Submit Another Form</a>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    @if($general->note)
      <p class="mb-2 footer-note"><strong>Note:</strong> {{ $general->note }}</p>
    @endif
    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
      <img src="{{ asset('general/' . ($general->footerlogo ?? 'logourdu.png')) }}" alt="Footer Logo" class="img-fluid" style="max-height: 60px;">
      @if($general->footer)
        <p class="mb-0 footer-note">{{ $general->footer }}</p>
      @endif
    </div>
  </div>
</div>

<!-- Bottom Credits -->
<footer class="mt-1 pt-1 pb-1 text-center" style="color: black;">
  <p class="mt-1 mb-1">
    &copy; {{ now()->year }} All Rights Reserved by Sunni Dawate Islami (SDI).<br>
    Developed By <a href="https://magnusideas.com" target="_blank" style="color:rgb(8, 58, 122); text-decoration: none;">Magnus Ideas Pvt. Ltd.</a>
  </p>
</footer>

</body>
</html>
