<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$type}} Reset Link Sent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .message {
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>{{$type}} Reset Link Sent</h1>
            <p>An email with instructions to reset your {{$type}} has been sent to your email address.</p>
        </div>
    </div>
</body>
</html>
