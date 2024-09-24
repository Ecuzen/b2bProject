<!DOCTYPE html>
<html>
<head>
    <title>Request Rejected</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        h1 {
            color: #FF0000;
        }

        p {
            margin-bottom: 10px;
        }

        .button {
            display: inline-block;
            background-color: #FF0000;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .logo img {
            max-width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Request Rejected</h1>
        <p>Dear {{$data['name']}},</p>
        <p>We regret to inform you that your recent request has been rejected. We understand that this may be disappointing, but we would like to provide some context for the decision:</p>
        <p>If you have any questions or require further clarification regarding the rejection, please don't hesitate to contact our support team at {{$data['support']}}. We are more than willing to address any concerns you may have.</p>
        <p>We appreciate your understanding and hope that we can continue to work together in the future. Thank you for your interest and efforts.</p>
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
