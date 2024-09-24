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
    .logo {
      text-align: center;
    }

    .logo img {
      max-width: 100%; 
      height: auto;
      max-height: 100px; 
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
    <div class="logo">
        <img src="{{$logo}}" alt="Company Logo">
    </div>
    <h1>Receipt</h1>
    
    <div class="info">
        @foreach($headerData as $key=>$val)
            <p><strong>{{ucfirst(str_replace('_',' ',$key))}} :</strong> {{$val}}</p>
        @endforeach
    </div>
    
    <table class="table">
      <tbody>
        @foreach($bodyData as $key => $val)
            
            @if($key == 'mini_statement')
                <hr>
                <table class="table">
                    <thead>
                        <tr class="">
                            <th>Date</th>
                            <th>Txn Type</th>
                            <th>Amount</th>
                            <th>Narration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($val as $item)
                            @if($item->txnType == 'Dr')
                                <tr class="text-danger">
                                  <td>{{$item->date}}</td>
                                  <td>{{$item->txnType}}</td>
                                  <td>{{$item->amount}}</td>
                                  <td>{{$item->narration}}</td>
                                </tr>
                            @else
                                <tr class="text-success">
                                  <td>{{$item->date}}</td>
                                  <td>{{$item->txnType}}</td>
                                  <td>{{$item->amount}}</td>
                                  <td>{{$item->narration}}</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4">No transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @php
                continue;
                @endphp
            @endif
            <tr>
              <td>{{ucfirst(str_replace('_',' ',$key))}} :</td>
              <td>{{$val}}</td>
            </tr>
        @endforeach
      </tbody>
    </table>
    
    <div class="total">
      <p>Thank you for using our services!</p>
      <p>Please contact us at {{$support}} for any inquiries.</p>
    </div>
  </div>
</body>
</html>
