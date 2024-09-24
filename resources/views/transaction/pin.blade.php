<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Entry</title>
    <style>
        .otp-input {
            width: 30px;
            height: 30px;
            text-align: center;
            margin: 0 5px;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Enter your 4-digit OTP</h1>
    <form id="otp-form">
        <input type="text" class="otp-input" maxlength="1" autofocus>
        <input type="text" class="otp-input" maxlength="1">
        <input type="text" class="otp-input" maxlength="1">
        <input type="text" class="otp-input" maxlength="1">
    </form>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const currentValue = e.target.value;

                if (currentValue.length === 1) {
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    } else {
                        // All OTP digits entered
                        console.log('OTP:', getOTPValue());
                    }
                }

                if (currentValue.length === 0) {
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                    }
                }
            });

            input.addEventListener('keydown', (e) => {
                const isNumberKey = /^[0-9]$/i.test(e.key);
                if (!isNumberKey && e.key !== 'Backspace') {
                    e.preventDefault();
                }
            });
        });

        function getOTPValue() {
            let otpValue = '';
            otpInputs.forEach(input => {
                otpValue += input.value;
            });
            return otpValue;
        }
    </script>
</body>
</html>
