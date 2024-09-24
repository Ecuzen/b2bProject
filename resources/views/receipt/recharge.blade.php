<!DOCTYPE html>
<html>
<head>
  <title>Receipt</title>
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
    
    .table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    
    .table th,
    .table td {
      padding: 8px;
      border-bottom: 1px solid #ccc;
    }
    
    .total {
      text-align: right;
      margin-top: 20px;
    }
    
    .total p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Receipt</h1>
    
    <div class="info">
      <p><strong>Date:</strong> {{$date}}</p>
      <p><strong>Mobile:</strong>{{$mobile}}</p>
      <p><strong>Transaction ID:</strong> {{$txnid}}</p>
      <p><strong>Operator :</strong> {{ $operator }}</p>
      <p><strong>Txn Status:</strong> {{ $status }}</p>
    </div>
    
    <table class="table">
      <thead>
        <tr>
          <th>Item</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>{{$item1}}</td>
          <td>{{$amount1}}</td>
        </tr>
        <tr>
          <td>{{$item2}}</td>
          <td>{{$amount2}}</td>
        </tr>
        <tr>
          <td ><strong>Total</strong></td>
          <td><strong>{{$total}}</strong></td>
        </tr>
      </tbody>
    </table>
    
    <div class="total">
      <p>Thank you for using our services!</p>
      <p>Please contact us at {{$support}} for any inquiries.</p>
    </div>
  </div>
</body>
</html>
