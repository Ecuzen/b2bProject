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
    .receipt-logo{
        text-align: center;
    }
    .receipt-logo img{
        height: 50px;
        width: auto;
    }
    #downloadReceiptPdf{
        margin-top: 20px;
    }
  </style>
</head>
<body>
  <div id="receiptTxn" class="container">
    <!--<h1>Receipt</h1>-->
    
    <div class="receipt-logo">
     <p>
         <img src="{{ $logo }}" alt="logo-large" class="logo-lg logo-dark">
     </p>    
    </div>
    <div class="info">
      <p><strong>Date:</strong> {{$date}}</p>
      <p><strong>Customer:</strong>{{$name}}</p>
      <p><strong>Transaction ID:</strong> {{$txnid}}</p>
      <p><strong>Account Number:</strong> {{ $account }}</p>
      <p><strong>IFSC Code:</strong> {{ $ifsc }}</p>
      <p><strong>Txn Status:</strong> {{ $status }}</p>
      <p><strong>Txn Mode:</strong> {{ $mode }}</p>
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
    <!--<button id="downloadReceiptPdf" class="btn btn-blue text-white" > <i class="fa-solid fa-print"></i> Print</button>-->
    <!--<button type="button" class="btn btn-light-primary text-danger font-medium waves-effect text-start  printButton print-page"><i class="ti ti-printer fs-5"></i> Print </button>-->
    <!--<button onclick="convertToPdf" class="btn btn-blue text-white" > <i class="fa-solid fa-print"></i> Print</button>-->
  </div>
  
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js'></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
  
  <script>
    $(document).ready(function() {
      $('#downloadReceiptPdf').click(function () { 
        html2canvas($('#receiptTxn')[0]).then(canvas => {
          var imgData = canvas.toDataURL('image/png');
          var doc = new jsPDF();
          doc.addImage(imgData, 'PNG', 10, 10);
          doc.save('Receipt');
        }).catch(function(error) {
          console.error('Error generating PDF', error);
        });
      });
    });
  </script>
  
  <!--<script>-->
  <!--    function convertToPdf()-->
  <!--      {-->
  <!--          html2canvas(document.querySelector('#certificate'), {useCORS: true}).then(function(canvas) {-->
  <!--            let img = new Image();-->
  <!--            img.src = canvas.toDataURL('image/png');-->
  <!--            img.onload = function () {-->
  <!--              let pdf = new jsPDF('landscape', 'mm', 'a4');-->
  <!--              pdf.addImage(img, 0, 0, pdf.internal.pageSize.width, pdf.internal.pageSize.height);-->
  <!--              pdf.save('certificate.pdf');-->
  <!--            };-->
  <!--          });-->
  <!--      }-->
        
  <!--</script>-->
</body>
</html>
