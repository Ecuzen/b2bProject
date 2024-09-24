<!DOCTYPE html>
<html>
<head>
  <title>Fetch Bill</title>
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
    <h1>Fetch Bill</h1>
    
    <div class="info">
      <p><strong>Message:</strong> {{$message}}</p>
      <p><strong>Customer:</strong> {{$name}}</p>
      <p><strong>{{$paramname}}:</strong> {{$paramvalue}}</p>
    </div>
    
    <table class="table">
      <tbody>
        @if(isset($item1value))
            <tr>
              <td>{{$item1}}</td>
              <td>{{$item1value}}</td>
            </tr>
        @endif
        
        
         @if(isset($item2value))
            <tr>
              <td>{{$item2}}</td>
              <td>{{$item2value}}</td>
            </tr>
        @endif
        
        
        <tr>
          <td ><strong>{{$item3}}</strong></td>
          <td><strong>{{$item3value}}</strong></td>
          <input type="hidden" value="{{$item3value}}" id="amount">
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
