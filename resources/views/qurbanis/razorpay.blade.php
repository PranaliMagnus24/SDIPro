<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h1>Payment for Qurbani</h1>
    <div id="payment-form">
        <button id="pay-button">Pay Now</button>
    </div>

    <script>
        var options = {
            "key": "{{ config('razorpay.key') }}", // Your Razorpay API key
            "amount": "{{ $order->amount }}", // Amount in paise
            "currency": "INR",
            "order_id": "{{ $order->id }}", // Order ID generated from Razorpay
            "name": "Qurbani Payment",
            "description": "Payment for Qurbani Hisse",
            "image": "{{ asset('images/logo.png') }}", // Optional logo image
            "handler": function (response) {
                alert('Payment Successful. Payment ID: ' + response.razorpay_payment_id);
                // Optionally, you can send the payment details to your server here.
                window.location.href = "{{ route('qurbanis.index') }}"; // Redirect to the index page after successful payment
            },
            "prefill": {
                "name": "{{ Auth::user()->name ?? 'Guest' }}", // Prefill name
                "email": "{{ Auth::user()->email ?? 'guest@example.com' }}", // Prefill email
                "contact": "{{ Auth::user()->mobile ?? '' }}" // Prefill mobile number
            },
            "theme": {
                "color": "#F37254"
            }
        };

        var rzp1 = new Razorpay(options);
        document.getElementById('pay-button').onclick = function(e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
