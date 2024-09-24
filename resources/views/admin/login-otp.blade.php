<div class="form-group mb-2">
    <label class="form-label" for="username">OTP</label>
        <div class="input-group">                                                                                         
            <input type="text" class="form-control" name="username" id="login-otp" placeholder="Enter OTP">
        </div>                                    
</div>
                    
<div class="form-group mb-0 row">
    <div class="col-12">
        <button class="btn btn-primary w-100 waves-effect waves-light admin-login-verify" type="button">Verify OTP <i class="fas fa-sign-in-alt ms-1"></i></button>
    </div>
</div>                           

<script>
    $(".admin-login-verify").click(function(){
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition,showError);
      } else {
        alert("GeoLocation is not supported by your browser.");
      }
})

function showPosition(position) {
    var lat = position.coords.latitude;
    var log = position.coords.longitude;
    login(lat,log);
}
function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      alert("Please Allow Location. Please Try again Later");
      break;
    case error.POSITION_UNAVAILABLE:
       alert("Location Error. Please Try again Later");
      break;
    case error.TIMEOUT:
     alert("Location Timeout Error. Please Try again Later");
      break;
    case error.UNKNOWN_ERROR:
      alert("Error in Location. Please try again later");
      break;
  }
}

function login(lat,log)
{
    var previous = $(".admin-login-verify").html();
    $(".admin-login-verify").html("Waiting!!!");
    $(".admin-login-verify").prop("disabled", true);
    var otp = $("#login-otp").val();
    if(otp == "")
    {
        info('Please enter 6 digit otp!!');
        $(".admin-login-verify").html(previous);
        $(".admin-login-verify").prop("disabled", false);
        return;
    }
    $.ajax({
        url : '/admin-login-verify',
        method : 'post',
        data : {
            'otp' : otp,
            'lat' : lat,
            'log' : log,
            '_token' : "{{csrf_token()}}"
        },
        success:function(data)
        {
            $(".admin-login-verify").html(previous);
            $(".admin-login-verify").prop("disabled", false);
            if(data.status == 'SUCCESS')
            {
                successReload(data.message);
            }
            else
            {
                error(data.message);
            }
        }
    })
}



function successReload(text)
        {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'success',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
</script>