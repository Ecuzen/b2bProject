<!DOCTYPE html>
<html>
<head>
  <title>User Credentials</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
    }
    h1 {
      color: #333333;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      padding: 20px;
    }
    .message {
      background-color: #f6f6f6;
      padding: 20px;
      border-radius: 5px;
    }
    .credentials {
      margin-top: 20px;
    }
    .credentials p {
      margin: 0;
    }
    .credentials p span {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>User Credentials</h1>
    <div class="message">
      <p>Dear User,</p>
      <p>Thank you for registering with our platform. Here are your login credentials:</p>
      <div class="credentials">
        <p><span>Username:</span> {{$data['username']}}</p>
        <p><span>Password:</span> {{$data['password']}}</p>
        <p><span>PIN:</span> {{$data['pin']}}</p>
      </div>
      <p>Please keep these credentials confidential and do not share them with anyone.</p>
      <p>If you have any questions or need further assistance, please don't hesitate to contact our support team.</p>
      <p>Thank you,</p>
      <p>The {{ env('APP_NAME') }} Team</p>
    </div>
  </div>
</body>
</html>
