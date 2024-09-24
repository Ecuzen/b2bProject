<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>
    <style>
        .otp-input {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .otp-input input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 20px;
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="otp-input">
        <input type="text" maxlength="1" id="digit1" onkeyup="jumpToNext(this, 'digit2')" onkeydown="handleBackspace(1)"/>
        <input type="text" maxlength="1" id="digit2" onkeyup="jumpToNext(this, 'digit3')" onkeydown="handleBackspace(2)"/>
        <input type="text" maxlength="1" id="digit3" onkeyup="jumpToNext(this, 'digit4')" onkeydown="handleBackspace(3)"/>
        <input type="text" maxlength="1" id="digit4" onkeyup="jumpToNext(this, 'digit5')" onkeydown="handleBackspace(4)"/>
        <input type="text" maxlength="1" id="digit5" onkeyup="jumpToNext(this, 'digit6')" onkeydown="handleBackspace(5)"/>
        <input type="text" maxlength="1" id="digit6" onkeyup="jumpToNext(this, 'digit6')" onkeydown="handleBackspace(6)"/>
    </div>
</body>
</html>
