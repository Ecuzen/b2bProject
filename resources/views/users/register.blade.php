<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/authentication-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:44 GMT -->
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
    <link rel="shortcut icon" type="image/png" href="{{$logo}}" />
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
                            src="../../assets/images/psa-buy.png"
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
                      <form id="signup">
                        <div class="mb-3">
                          <label for="usernameInput" class="form-label">First Name</label>
                          <input type="text" class="form-control" id="usernameInput" aria-describedby="emailHelp" name="firstName" >
                        </div>
                        <div class="mb-4">
                          <label for="passwordInput" class="form-label">Last Name</label>
                          <input type="text" class="form-control" id="passwordInput" name="lastName" >
                        </div>
                        <div class="mb-4">
                          <label for="mobileInput" class="form-label">Phone No.</label>
                          <input type="text" class="form-control numeric-input" id="mobileInput" name="phone" maxlength="10" minlength="10">
                        </div>
                        <div class="mb-4">
                          <label for="emailInput" class="form-label">Email</label>
                          <input type="email" class="form-control" id="emailInput" name="email" >
                        </div>
                        <div class="mb-4">
                          <label for="soleSelect" class="form-label">Select Role</label>
                          <select class="form-control" name="role" id="soleSelect" >
                                @foreach($role as $rol)
                                    <option value="{{ $rol->id }}">{{$rol->name}}</option>
                                @endforeach
                          </select>
                        </div>
                        <button class="btn btn-primary w-100 py-8 mb-4 rounded-2" type="submit">Sign Up</button>
                        <div class="d-flex align-items-center justify-content-center">
                          <p class="fs-4 mb-0 fw-medium">Already have an account ?</p>
                          <a class="text-primary fw-medium ms-2" href="{{route('home')}}">Login</a>
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
            
            var numericInputs = document.getElementsByClassName('numeric-input');
             for (var i = 0; i < numericInputs.length; i++) {
                 numericInputs[i].addEventListener('input', function(event) {
                     this.value = this.value.replace(/[^0-9]/g, '');
                 });
             }
            $("#signup").on('submit',function(e){
                e.preventDefault();
                var allFieldsFilled = true;
                $(this).find('input').each(function() {
                  if ($(this).val() === '') {
                      allFieldsFilled = false;
                    error('All fields are required!!')
                    return;
                  }
                });
                if(!allFieldsFilled)
                return;
                var formData = new FormData(this);
                 var csrfToken = "{{ csrf_token() }}";
                 formData.append('_token', csrfToken);
                 $.ajax({
                 url: "{{route('registerUser')}}",
                 method: 'POST',
                 data: formData,
                 contentType: false,
                 processData: false,
                 success: function(data) {
                     if (data.status == 'SUCCESS') {
                         successReload(data.message);
                        
                     } else {
                         error(data.message);
                     }
                 }
             });
            })
        })
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