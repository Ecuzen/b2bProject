<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:44 GMT-->
<head>
    <!--  Title -->
    <title>{{$title}}</title>
    <!--  Required Meta Tag -->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!--  Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{$fav}}" />
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="../../assets/dist/css/style.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="../../assets/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        .transparent-card {
            background-color: rgba(255, 255, 255, 0.5); /* Adjust the alpha value (0.5) for the desired level of transparency */
        }
    </style>
  </head>
  <body>
    <!-- Preloader -->
    <div class="preloader">
      <img src="{{$logo}}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
      <img src="{{$logo}}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100">
        <div class="position-relative z-index-5">
          <div class="row">
            <div class="col-xl-7 col-xxl-8">
              <a href="/" class="text-nowrap logo-img d-block px-4 py-9 w-100">
                <img src="{{$logo}}" width="180" alt="">
              </a>
              <div class="d-none d-xl-flex align-items-center justify-content-center" style="height: calc(100vh - 80px);">
               <div class="col-lg-6">
                    <div
                      id="carouselExampleSlidesOnly"
                      class="carousel slide carousel-dark"
                      data-bs-ride="carousel"
                    >
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                          <img
                            src="../../assets/images/banners/aeps.png"
                            class="d-block w-100"
                            alt="..."
                          />
                        </div>
                        <div class="carousel-item">
                          <img
                            src="../../assets/images/aeps-logo.png"
                            class="d-block w-100"
                            alt="..."
                          />
                        </div>
                        <div class="carousel-item">
                          <img
                            src="../../assets/images/dmtlogo.png"
                            class="d-block w-100"
                            alt="..."
                          />
                        </div>
                      </div>
                    </div>
              </div>
              </div>
            </div>
            <div class="col-xl-5 col-xxl-4">
              <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
                <div class="col-sm-8 col-md-6 col-xl-9">
                  <h2 class="mb-3 fs-7 fw-bolder head-title">Welcome to {{$title}}</h2>
                  <div class="change-for-pin">
                      <form id="signin">
                        <div class="mb-3">
                          <label for="usernameInput" class="form-label">Username</label>
                          <input type="text" class="form-control" id="usernameInput" aria-describedby="emailHelp" name="username">
                        </div>
                        <div class="mb-4">
                          <label for="passwordInput" class="form-label">Password</label>
                          <input type="password" class="form-control" id="passwordInput" name="password">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                          <div class="form-check">
                            <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                            <label class="form-check-label text-dark" for="flexCheckChecked">
                              Remeber this Device
                            </label>
                          </div>
                          <div class="text-primary fw-medium forget-pass cursor-pointer" >Forgot Password ?</div>
                        </div>
                        <button class="btn btn-primary w-100 py-8 mb-4 rounded-2" type="submit">Sign In</button>
                        <div class="d-flex align-items-center justify-content-center">
                          <p class="fs-4 mb-0 fw-medium">New to {{$title}}?</p>
                          <a class="text-primary fw-medium ms-2" href="{{route('newUserRegister')}}">Create an account</a>
                        </div>
                      </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
        $(document).ready(function(){
            $("#signin").on('submit',function(e){
                e.preventDefault();
                var username = $("#usernameInput").val();
                var password = $("#passwordInput").val();
                if(username == "" || password == "")
                {
                    error('Please share all fields!!');
                    return;
                }
                $.ajax({
            		  url: "/login",
            		  method : "POST",
            		  data : {
            		      "_token": "{{ csrf_token() }}",
            			  "username" : username,
            			  "password" : password
            		  },
            		  success:function(data,status)
            		  {
                        var n = data.includes("Sign");
                      if(n)
        			  {
        			  	   $(".change-for-pin").html(data);
        			  }
        			  if(!n)
        			  {
        				  error(data);
        			  }
            		  }
            	  });
            });
            $(".forget-pass").click(function(){
                $.ajax({
                    url : '/forgot-pass/password',
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
        function error(text)
        {
            Swal.fire({
              title: 'Error!',
              text: text,
              icon: 'error',
              confirmButtonText: 'OK',
              customClass: {
                icon: 'swal2-icon swal2-error swal2-animate-error-icon',
              }
            });
        }
        function success(text)
        {
            Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'success',
              text : text,
              confirmButtonText: 'OK',
              /*timer: 1500*/
            });
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
    <!--  Import Js Files -->
    <script src="../../assets/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../assets/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../../assets/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="../../assets/dist/js/app.min.js"></script>
    <script src="../../assets/dist/js/app.init.js"></script>
    <script src="../../assets/dist/js/app-style-switcher.js"></script>
    <script src="../../assets/dist/js/sidebarmenu.js"></script>
    
    <script src="../../assets/dist/js/custom.js"></script>
  </body>

<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:44 GMT -->
</html>