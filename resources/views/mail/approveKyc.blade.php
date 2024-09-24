<!DOCTYPE html>
<html>
<head>
    <title>KYC Approved</title>
    <style>
        .logo img {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc;">
        <h2 style="color: #007BFF;">KYC Approved</h2>
        <p>Dear {{$data['name']}},</p>
        <p>We are pleased to inform you that your KYC verification has been successfully approved.</p>
        <p>Your account is now fully verified, and you can enjoy all the benefits and services we offer.</p>
        <p>If you have any questions or need assistance, feel free to contact our support team at {{$data['support']}}.</p>
        <p>Thank you for choosing us. We value your business and look forward to serving you.</p>
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
