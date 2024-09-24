<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>OTP Email</title>
</head>
<body>
  <div>
    <h2>OTP Verification</h2>
    <p>Dear User,</p>
    <p>Your One-Time Password (OTP) for verification is:</p>
    <h3 style="font-weight: bold; color: #ff6600;">{{$data['otp']}}</h3>
    <p>Please enter this OTP in the provided field to complete the verification process.</p>
    <p>This OTP is valid for a limited time period and should not be shared with anyone.</p>
    <p>Thank you for using our service!</p>
    <p>Sincerely,</p>
    <p>{{ env('APP_NAME') }}</p>
  </div>
</body>
</html>
