<!DOCTYPE html>
<html>
<head>
  <title>Transaction Debit Notification</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      padding: 20px;
    }
    
    .container {
      max-width: 600px;
      margin: 0 auto;
      background-color: #ffffff;
      border-radius: 5px;
      padding: 20px;
    }
    
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    
    .logo {
      width: 100px;
      height: auto;
    }
    
    .content {
      margin-bottom: 20px;
    }
    
    .content p {
      margin: 0 0 10px 0;
    }
    
    .footer {
      text-align: center;
      font-size: 14px;
      color: #999999;
    }
    
    .stamp {
      display: inline-block;
      margin-top: 20px;
    }
    
    .stamp img {
      width: 100px;
      height: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img class="logo" src="{{$data['logo']}}" alt="Ecuzen">
      <h1>Transaction Debit Notification</h1>
    </div>
    
    <div class="content">
      <p>Dear {{$data['name']}},</p>
      <p>This email is to inform you that a debit transaction has been made from your wallet.</p>
      <p>Transaction details:</p>
      <ul>
        <li>Date: {{$data['date']}}</li>
        <li>Amount: {{$data['amount']}}</li>
        <li>Description: {{$data['message']}}</li>
      </ul>
      <p>If you have any questions or concerns regarding this transaction, please contact our customer support.</p>
    </div>
    
    <div class="footer">
      <p>Thank you for using our services.</p>
      <p>Best regards,<br>{{ env('APP_NAME') }}</p>
    </div>
    
    <div class="stamp">
      <img src="{{$data['logo']}}" alt="{{ env('APP_NAME') }}">
    </div>
  </div>
</body>
</html>
