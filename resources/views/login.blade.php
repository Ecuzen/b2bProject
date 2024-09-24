<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:44 GMT -->
<head>
    <!--  Title -->
    <title>Ecuzen</title>
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
    <!--<link rel="shortcut icon" type="image/png" href="{{url('assets')}}/dist/images/logos/favicon.png" />-->
    <link rel="shortcut icon" type="image/png" href="{{url('assets')}}/images/bg-remove-logo.png" />
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="{{url('assets')}}/dist/css/style.min.css" />
    <style>
    .btn-sign{
        background-color: #151747;
            color: #fff;
    }
        .btn-sign:hover{
            background-color: rgba(247, 47, 32, 1) !important;
            color: #fff;
        }
        .authentication-login{
            width: 60%;
            margin: auto;
            height: 60vh;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            border-radius: 20px;
            background-image: url('https://img.freepik.com/free-vector/designer-girl-concept-illustration_114360-2090.jpg?w=360');
            background-size: 220px;
            background-repeat: no-repeat;
            background-position: top right;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
        }
        .logo-login{
            position: absolute;
            z-index: 11111;
            left: 50%;
            transform: translate(-50%,0);
        }
        .login-main{
            background: url('../public/assets/dist/images/new-folder/login-bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
  </head>
  <body>
    <!-- Preloader -->
    <div class="preloader">
      <!--<img src="{{url('assets')}}/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />-->
      <img src="{{url('assets')}}/images/logo.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!-- Preloader -->
    <div class="preloader">
      <!--<img src="{{url('assets')}}/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />-->
      <img src="{{url('assets')}}/images/logo.png" alt="loader" class="lds-ripple img-fluid" />
    </div>
    <!--  Body Wrapper -->
    <div class="page-wrapper login-main" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <div class="position-relative overflow-hidden radial-gradient min-vh-100">
        <div class=" z-index-5">
          <div class="row">
            <div class="col-xl-12 col-xxl-12">
              <a href="index.html" class="text-nowrap logo-img d-flex px-4 py-9 w-100">
                <!--<img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg" width="180" alt="">-->
                <img class="mx-auto py-3 logo-login" src="{{url('assets')}}/images/logo.png" width="180" alt="">
              </a>
              
            </div>
          </div>
            <div>
              <div class="authentication-login bg-body row justify-content-center align-items-center p-4">
                <div class="col-sm-8 col-md-6 col-xl-9">
                  <h2 class="mb-3 fs-7 fw-bolder text-center">Welcome to Ecuzen</h2>
                  <!--<p class=" mb-9">Your Admin Dashboard</p>-->
                  <!--<div class="row">
                    <div class="col-6 mb-2 mb-sm-0">
                      <a class="btn btn-white text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/google-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
                        <span class="d-none d-sm-block me-1 flex-shrink-0">Sign in with</span>Google
                      </a>
                    </div>
                    <div class="col-6">
                      <a class="btn btn-white text-dark border fw-normal d-flex align-items-center justify-content-center rounded-2 py-8" href="javascript:void(0)" role="button">
                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/facebook-icon.svg" alt="" class="img-fluid me-2" width="18" height="18">
                        <span class="d-none d-sm-block me-1 flex-shrink-0">Sign in with</span>FB
                      </a>
                    </div>
                  </div>
                  <div class="position-relative text-center my-4">
                    <p class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">or sign in with</p>
                    <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                  </div>-->
                  <form class="w-60 mx-auto">
                      <div class="d-flex flex-column justify-content-center" id="cont">
                        <div class="mb-3">
                          <label for="exampleInputEmail1" class="form-label">Username</label>
                          <input type="email" class="form-control" id="name" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-4">
                          <label for="exampleInputPassword1" class="form-label">Password</label>
                          <input type="password" class="form-control" id="password">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-4">
                          <div class="form-check">
                            <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                            <label class="form-check-label text-dark" for="flexCheckChecked">
                              Remeber this Device
                            </label>
                          </div>
                          <a class="text-primary fw-medium" style="cursor: pointer;" onclick="forgotpass();">Forgot Password ?</a>
                        </div>
                        <a class="btn w-30 mx-auto py-8 mb-4 rounded-2 btn-sign" onclick="login()">Sign In</a>
                        <!--<div class="d-flex align-items-center justify-content-center">
                          <p class="fs-4 mb-0 fw-medium">New to Modernize?</p>
                          <a class="text-primary fw-medium ms-2" href="authentication-register.html">Create an account</a>
                        </div>-->
                    </div>
                  </form>
                </div>
              </div>
            </div>
        </div>
        
      </div>
    </div>
    
    	@csrf
		<input type="hidden" id="_token" value="{{ csrf_token() }}" />
    <script>
        
        function login()
            	{
                  var username = $("#name").val();
                  var password = $("#password").val();
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
        			  	$("#cont").html(data);
        			  }
        			  if(!n)
        			  {
        				  error(data);
        			  }
            		  }
            	  });
            
            	}
            	
            	
            	
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
                                location.href="/";
            				}
            
            				if(data != 1)
            				{
                               error(data);
            				}
            			}
            		});
            	}
            	
        function forgotpass()
        {
            $.ajax({
                url : '/forgot-pass',
                method : 'get',
                data : {
                    'token' : '{{ csrf_token() }}'
                },
                success:function(data)
                {
                    $("errormsg").hide();
                    data = JSON.parse(data);
                    if(data.status == 'SUCCESS')
                    {
                        $("#cont").html(data.view);
                    }
                    else
                    {
                        error(data.msg);
                    }
                }
            })
        }
        
        function forgotpasssend()
        {
            var email = $("#femailid").val();
            var check = validateEmail(email);
            if(!check)
            {
                error('Please enter valid email');
                return;
            }
            $.ajax({
                url : 'forgot-pass-mail',
                method : 'POST',
                data : {
                    '_token' : '{{ csrf_token() }}',
                    'email' : email,
                },
                success:function(data)
                {
                    if(data.status == 'SUCCESS')
                    {
                        success(data.msg);
                    }
                    else
                    {
                        error(data.msg);
                    }
                }
            })
        }

function validateEmail(email) {
  var emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
  return emailPattern.test(email);
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
// $(document).ready(function(){
//     var numericInputs = document.getElementsByClassName('numeric-input');
//     for (var i = 0; i < numericInputs.length; i++) {
//         numericInputs[i].addEventListener('input', function(event) {
//             this.value = this.value.replace(/[^0-9]/g, '');
//         });
//     }
// })
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
    <!--  Import Js Files -->
    <script src="{{url('assets')}}/dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{url('assets')}}/dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="{{url('assets')}}/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!--  core files -->
    <script src="{{url('assets')}}/dist/js/app.min.js"></script>
    <script src="{{url('assets')}}/dist/js/app.init.js"></script>
    <script src="{{url('assets')}}/dist/js/app-style-switcher.js"></script>
    <script src="{{url('assets')}}/dist/js/sidebarmenu.js"></script>
    
    <script src="{{url('assets')}}/dist/js/custom.js"></script>
  </body>


</html>