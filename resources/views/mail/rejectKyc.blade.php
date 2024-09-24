<!DOCTYPE html>
<html>
<head>
    <title>KYC Rejection</title>
    <style>
        .logo img {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc;">
        <h2 style="color: #FF0000;">KYC Rejection</h2>
        <p>Dear {{$data['name']}},</p>
        <p>We regret to inform you that your KYC verification has been rejected.</p>
        <p>Please note that you may reapply for KYC verification by updating the required information and submitting the necessary documents.</p>
        <p>If you need further assistance or have any questions, feel free to contact our support team at {{$data['support']}}.</p>
        <p>Thank you for your understanding.</p>
        <p>Best regards,<br>
        {{ env('APP_NAME') }}<br>
        Support Executive<br>
        {{$data['company']}}<br>
        {{$data['support']}}</p>
        <div class="logo">
            <img src="{{$data['logo']}}" alt="{{$data['company']}} Logo">
        </div>
    </div>
</body>
</html>
