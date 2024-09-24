<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['type']}} Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        .content {
            font-size: 16px;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img class="logo" src="{{$data['logo']}}" alt="{{$data['company']}} Logo">
            <h1>{{$data['type']}} Reset</h1>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>We received a request to reset your {{$data['type']}}. Click the button below to reset your {{$data['type']}}:</p>
            <p><a class="button" href="{{ $data['reset_link'] }}">Reset {{$data['type']}}</a></p>
            <p>If you did not request a {{$data['type']}} reset, please ignore this email.</p>
            <p>Thank you,</p>
            <p>Team {{$data['company']}}</p>
        </div>
    </div>
</body>
</html>
