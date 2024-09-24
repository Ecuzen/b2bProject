<style>
            .btn-blue{
            background: #151747 !important;
        }
</style>
<form id="submit-pin">
    <div class="card-title text-center  mb-4">Please Enter PIN</div>
    @csrf
    <div class="form-group">
    	<input type="text" class="form-control numeric-input'" id="pin" placeholder="Enter PIN">
    </div>
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div class="form-check">
        
      </div>
      <div class="text-primary fw-medium forget-pin cursor-pointer mt-4" >Forgot PIN ?</div>
    </div>
    <div class="form-footer mt-1 d-flex ">
        <button type="submit" class="btn mx-auto btn-blue btn-block text-white mt-3" >Sign In</button>
    </div>
</form>
<script>
    $("#submit-pin").on('submit',function(e){
        e.preventDefault();
        verify();
    })
    function verify() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition,showError);
  } else {
    alert("GeoLocation is not supported by your browser.");
  }
}

function showPosition(position) {
  var lat = position.coords.latitude;
  var log = position.coords.longitude;    
  gotforlogin(lat,log);
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
	function  gotforlogin(lat,log)
	{
		var pin = $("#pin").val();

		$.ajax({

			url: "/loginverify",
			method : "POST",
			data : {
			    "_token": "{{ csrf_token() }}",
				"pin" : pin,
				"lat" : lat,
				"log" : log

			},
			success:function(data,status)
			{
				if (data == 1)
				{
				    success('Login Successfully');
                    location.href="{{route('home')}}";
				}

				if(data != 1)
				{
                   error(data);
				}
			}
		});
	}
	$(".forget-pin").click(function(){
                $.ajax({
                    url : '/forgot-pass/pin',
                    success:function(data)
                    {
                        $("errormsg").hide();
                        data = JSON.parse(data);
                        if(data.status == 'SUCCESS')
                        {
                            $(".head-title").hide();
                            $(".change-for-pin").html(data.view);
                        }
                        else
                        {
                            error(data.msg);
                        }
                    }
                })
            });
</script>