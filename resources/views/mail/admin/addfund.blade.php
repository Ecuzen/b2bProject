<!DOCTYPE html>
<html>
<head>
    <title>Wallet Fund Request</title>
</head>
<body>
    <h2>Wallet Fund Request</h2>
    <p>Hello,</p>
    <p>A wallet fund request has been submitted. Below are the details:</p>
    
    <table>
        <tr>
            <th>TXN ID:</th>
            <td>{{ $data['txnid'] }}</td>
        </tr>
        <tr>
            <th>User:</th>
            <td>{{ $data['username'] }}</td>
        </tr>
        <tr>
            <th>Amount:</th>
            <td>{{ $data['amount'] }}</td>
        </tr>
        <tr>
            <th>RRN:</th>
            <td>{{ $data['rrn'] }}</td>
        </tr>
    </table>
    
    <p>Please review the request and take appropriate action.</p>
    
    <p>Thank you.</p>
</body>
</html>
