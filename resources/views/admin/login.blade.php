
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{$title}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="{{$title}}" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{$fav}}">

        <!-- App css -->
        <link href="{{url('assets')}}/dashboard/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('assets')}}/dashboard/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="{{url('assets')}}/dashboard/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
        
    </head>

    <body class="account-body accountbg">

        <!-- Log In page -->
        <div class="container">
            <div class="row vh-100 d-flex justify-content-center">
                <div class="col-12 align-self-center">
                    <div class="row">
                        <div class="col-lg-5 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 auth-header-box">
                                    <div class="text-center p-3">
                                        <a href="/admin" class="logo logo-admin">
                                            <img src="{{$logo}}" height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Let's Get Started {{$title}}</h4>   
                                        <p class="text-muted  mb-0">Sign in to continue to {{$title}}.</p>  
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <center><h2 id="box-header">Login</h2></center>
                                    <div class="tab-content">
                                        <div class="tab-pane active p-3" id="LogIn_Tab" role="tabpanel">                                        
                                            <form class="form-horizontal auth-form" >
                
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="username">Username</label>
                                                    <div class="input-group">                                                                                         
                                                        <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                                                    </div>                                    
                                                </div><!--end form-group--> 
                    
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="userpassword">Password</label>                                            
                                                    <div class="input-group">                                  
                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                                                    </div>                               
                                                </div><!--end form-group--> 
                    
                                                <div class="form-group row my-3">
                                                    <div class="col-sm-6">
                                                        <div class="custom-control custom-switch switch-success">
                                                            <input type="checkbox" class="custom-control-input" id="customSwitchSuccess">
                                                            <label class="form-label text-muted" for="customSwitchSuccess">Remember me</label>
                                                        </div>
                                                    </div><!--end col--> 
                                                    <div class="col-sm-6 text-end">
                                                        <a href="auth-recover-pw.html" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>                                    
                                                    </div><!--end col--> 
                                                </div><!--end form-group--> 
                    
                                                <div class="form-group mb-0 row">
                                                    <div class="col-12">
                                                        <button class="btn btn-primary w-100 waves-effect waves-light admin-login" type="button">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                    </div><!--end col--> 
                                                </div> <!--end form-group-->                           
                                            </form><!--end form-->
                                        </div>
                                    </div>
                                </div><!--end card-body-->
                                <div class="card-body bg-light-alt text-center">
                                    <span class="text-muted d-none d-sm-inline-block">{{$title}} © <span id="currentYear"></span></span>                                            
                                </div>
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <!-- End Log In page -->

        


        <!-- jQuery  -->
        <script src="{{url('assets')}}/dashboard/assets/js/jquery.min.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/js/bootstrap.bundle.min.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/js/waves.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/js/feather.min.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/js/simplebar.min.js"></script>
        <script>
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
        
        function info(text)
        {
            Swal.fire({
                title: 'Error!',
                text: text,
                icon: 'info',
                confirmButtonText: 'OK',
                customClass: {
                    icon: 'swal2-icon swal2-error swal2-animate-error-icon',
                }
            });
        }
        
        $("#currentYear").text(new Date().getFullYear());
        $(".admin-login").click(function(){
            var $button = $(this);
            $button.data("previousHtml", $button.html());
            $button.html("Waiting!!!");
            $button.prop("disabled", true);
            var username = $("#username").val();
            var password = $("#password").val();
            if(username== "" || password == "")
            {
                Swal.fire({
                  title: 'Error!',
                  text: 'Username and Password is required',
                  icon: 'error',
                  confirmButtonText: 'OK',
                  customClass: {
                  icon: 'swal2-icon swal2-error swal2-animate-error-icon',
                  }
                });
                $button.html($button.data("previousHtml"));
                $button.prop("disabled", false);
                return;
            }
            $.ajax({
                url : '/admin-login',
                method : 'post',
                data : {
                    'username' : username,
                    'password' : password,
                    '_token' : "{{csrf_token()}}"
                },
                success:function(data)
                {
                    $button.html($button.data("previousHtml"));
                    $button.prop("disabled", false);
                    if(data.status == 'SUCCESS')
                    {
                        $('#box-header').html('Verify OTP')
                        $(".auth-form").html(data.view);
                    }
                    else
                    {
                        error(data.message);
                    }
                }
            })
        })
        </script>
        
    </body>

</html>