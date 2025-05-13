<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qurbani Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 100%; text-align: center; }
        .header { font-size: 20px; font-weight: bold; }
        .content { margin-top: 20px; font-size: 16px; }
        .footer { margin-top: 30px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Qurbani Receipt
        </div>
        <div class="content">
            <p><strong>Name:</strong> {{ $qurbani->contact_name }}</p>
            <p><strong>Mobile:</strong> {{ $qurbani->mobile }}</p>
            <p><strong>Receipt Book:</strong> {{ $qurbani->receipt_book }}</p>
            <p><strong>Payment Type:</strong> {{ $qurbani->payment_type }}</p>
            <p><strong>Transaction No:</strong> {{ $qurbani->transaction_number }}</p>
        </div>
        <div class="footer">
            <p>Thank you for your donation!</p>
        </div>
    </div>
</body>
</html>
