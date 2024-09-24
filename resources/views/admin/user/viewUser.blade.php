<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    
    .container {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    
    h1 {
      text-align: center;
    }
    
    .info {
      margin-top: 20px;
    }
    
    .info p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="info">
      <p><strong>Name:</strong> {{$name}}</p>
      <p><strong>Username:</strong>{{$username}}</p>
      <p><strong>Phone:</strong> {{$phone}}</p>
      <p><strong>Email:</strong> {{ $email}}</p>
      <p><strong>Wallet:</strong> {{ $wallet }}</p>
      <p><strong>Password:</strong> {{ $password }}</p>
      <p><strong>PIN:</strong> {{ $pin }}</p>
      <p><strong>Profile Kyc:</strong> {{ $profileKyc }}</p>
      <p><strong>AEPS Kyc:</strong> {{ $aepsKyc }}</p>
    </div>
  </div>
</body>
</html>
