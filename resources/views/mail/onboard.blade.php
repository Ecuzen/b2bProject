<!DOCTYPE html>
<html>
<head>
    <title>Welcome to {{$data['company']}}</title>
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

        .credentials {
            background-color: #f0f0f0;
            padding: 10px;
        }

        .credentials label {
            font-weight: bold;
        }

        .credentials p {
            margin: 5px 0;
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
        <h1>Welcome to {{$data['company']}}!</h1>
        <p>Dear {{$data['name']}},</p>
        <p>Welcome aboard to {{$data['company']}}! We are delighted to have you as part of our team. This email contains your account details to access our systems.</p>

        <div class="credentials">
            <p><label>Username:</label> {{$data['username']}}</p>
            <p><label>Password:</label> {{$data['password']}}</p>
            <p><label>Pin:</label> {{$data['pin']}}</p>
            <p>Please keep this information secure and do not share it with anyone. We recommend changing your password upon your first login for added security.</p>
        </div>

        <p>To get started, please follow these steps:</p>
        <ol>
            <li>Access the Login Page: Go to {{$data['loginUrl']}}</li>
            <li>Enter Your Credentials: Use the username and password provided in this email to log in.</li>
            <li>Explore Our Systems: Once logged in, you will have access to our various systems and tools to support you in your role.</li>
        </ol>

        <p>Our team is here to assist you with any questions or technical support you may need. If you encounter any issues or have any questions during the onboarding process, please don't hesitate to reach out to our support team at {{$data['support']}}.</p>

        <p>Once again, welcome to {{$data['company']}}! We are confident that you will make a valuable contribution to our team.</p>

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
