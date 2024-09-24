<!DOCTYPE html>
<html>
<head>
    <title>Support Ticket Closed</title>
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
            color: #007BFF;
        }

        p {
            margin-bottom: 10px;
        }

        .button {
            display: inline-block;
            background-color: #007BFF;
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
        <h1>Support Ticket Closed</h1>
        <p>Dear {{$data['name']}},</p>
        <p>We are writing to inform you that your support ticket with reference number {{$data['ticketId']}} has been successfully resolved and is now closed.</p>
        <p>Details of the Ticket:</p>
        <ul>
            <li>Ticket Number: {{$data['ticketId']}}</li>
            <li>Issue: {{$data['type']}}</li>
            <li>Status: Closed</li>
            <li>Resolution: {{$data['msg']}}</li>
        </ul>
        <p>If you have any further questions or encounter any related issues, please don't hesitate to reach out to our support team at {{$data['support']}}. We are here to assist you.</p>
        <p>Thank you for choosing us. We value your business and look forward to serving you in the future.</p>
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
