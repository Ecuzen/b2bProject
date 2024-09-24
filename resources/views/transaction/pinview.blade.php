<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enter Your PIN</title>
<style>
  .otp-container {
    display: flex;
    justify-content: space-between;
    width: 300px;
    margin: 50px auto;
  }

  .otp-box {
    width: 40px;
    height: 40px;
    text-align: center;
    font-size: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
</style>
</head>
<body>
     <!--<h1>Enter your 4-digit OTP</h1>-->
<div class="otp-container">
  <input class="otp-box" type="text" maxlength="1">
  <input class="otp-box" type="text" maxlength="1">
  <input class="otp-box" type="text" maxlength="1">
  <input class="otp-box" type="text" maxlength="1">
</div>
<div id="otpValue"></div>
<script>
$('.otp-box').on('input', function() {
  const inputValue = $(this).val();

  if (inputValue.length === 1) {
    const $nextBox = $(this).next('.otp-box');
    if ($nextBox.length) {
      $nextBox.focus();
    } else {
      transactions(getOTPValue());
    }
  } else if (inputValue.length === 0) {
    const $prevBox = $(this).prev('.otp-box');
    if ($prevBox.length) {
      $prevBox.focus();
    }
  }
});

$('.otp-box').on('keydown', function(e) {
  if (e.keyCode === 8 && $(this).val().length === 0) {
    const $prevBox = $(this).prev('.otp-box');
    if ($prevBox.length) {
      $prevBox.val('').focus();
    }
  }
});

function getOTPValue() {
  let otp = '';
  $('.otp-box').each(function() {
    otp += $(this).val();
  });
  return otp;
}
</script>
</body>
</html>
