<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{$title}}</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="handheldfriendly" content="true" />
        <meta name="MobileOptimized" content="width" />
        <meta name="description" content="Ecuzen" />
        <meta name="author" content="" />
        <meta name="keywords" content="Ecuzen" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!--  Favicon -->
        <!--<link rel="shortcut icon" type="image/png" href="{{url('assets')}}/dist/images/logos/favicon.png" />-->
        <link rel="shortcut icon" type="image/png" href="{{$logo}}" />
        <!-- Owl Carousel  -->
        <link rel="stylesheet" href="{{url('assets')}}/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css" />

        <!-- Core Css -->
        <link id="themeColors" rel="stylesheet" href="{{url('assets')}}/dist/css/style.min.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css" />

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
            integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .spinner {
              border: 4px solid rgba(0, 0, 0, 0.1);
              border-left-color: #7983ff;
              border-radius: 50%;
              width: 40px;
              height: 40px;
              animation: spin 1s linear infinite;
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              z-index: 9999;
            }
            
            @keyframes spin {
              0% {
                transform: translate(-50%, -50%) rotate(0deg);
              }
              100% {
                transform: translate(-50%, -50%) rotate(360deg);
              }
            }
            
            /* Additional styling to visually disable the body */
            .disabled-body {
              pointer-events: none;
              opacity: 0.5;
              position: relative;
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
            <!--<img src="{{url('assets')}}/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />-->
            <img src="{{$logo}}" alt="loader" class="lds-ripple img-fluid" />
        </div>

        <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
            <div class="body-wrapper">
                <div class="container-fluid">
                    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
                        <div class="card-body px-4 py-3">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <h4 class="fw-semibold mb-8">Kyc Form</h4>
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item">
                                                <a class="text-muted" href="index.html">Home</a>
                                            </li>
                                            <li class="breadcrumb-item" aria-current="page">Kyc Form</li>
                                        </ol>
                                    </nav>
                                </div>
                                <div class="col-3">
                                    <div class="text-center mb-n5">
                                        <img src="{{url('assets')}}/images/kyc-logo.png" alt="" class="img-fluid mb-n4" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <section>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Aadhar Verification</h5>
                                        <form class="aadhar-otp">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Enter your Aadhar number" id="aadhar" name="aadhar" maxlength="12" />
                                                <label><i class="ti ti-password-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3"></span>Enter your Aadhar number</label>
                                            </div>
                                            <div class="form-floating mb-3 otp-section">
                                                <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Enter your Aadhar number" id="otp" name="otp" maxlength="10" />
                                                <label><i class="ti ti-123 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3"></span>Enter OTP</label>
                                            </div>

                                            <div class="d-md-flex align-items-center">
                                                <div class="mt-3 mt-md-0 mx-auto">
                                                    <button type="submit" class="btn btn-primary font-medium rounded-pill px-4 ">
                                                        <div class="d-flex align-items-center text-white form-button">
                                                            <i class="ti ti-send me-2 fs-4"></i>
                                                            Send OTP
                                                        </div>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <script src="{{url('assets')}}/dist/libs/jquery/dist/jquery.min.js"></script>
        <script src="{{url('assets')}}/dist/libs/simplebar/dist/simplebar.min.js"></script>
        <script src="{{url('assets')}}/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
        <!-- ---------------------------------------------- -->
        <!-- core files -->
        <!-- ---------------------------------------------- -->
        <script src="{{url('assets')}}/dist/js/app.min.js"></script>
        <script src="{{url('assets')}}/dist/js/app.init.js"></script>
        <script src="{{url('assets')}}/dist/js/app-style-switcher.js"></script>
        <script src="{{url('assets')}}/dist/js/sidebarmenu.js"></script>
        <script src="{{url('assets')}}/dist/js/custom.js"></script>
        <script src="{{url('assets')}}/dist/libs/prismjs/prism.js"></script>
        <!-- ---------------------------------------------- -->
        <!-- current page js files -->
        <!-- ---------------------------------------------- -->
        <script src="{{url('assets')}}/dist/libs/jquery-steps/build/jquery.steps.min.js"></script>
        <script src="{{url('assets')}}/dist/libs/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="{{url('assets')}}/dist/js/forms/form-wizard.js?v=1.6.1"></script>

        <script>
        var txnid;
            $(document).ready(function () {
                $(".otp-section").hide();
                var numericInputs = document.getElementsByClassName('numeric-input');
                for (var i = 0; i < numericInputs.length; i++) {
                    numericInputs[i].addEventListener('input', function(event) {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                }
                Swal.fire({
                    position: "center",
                    icon: "info",
                    title: "Information",
                    text: "Please complete your kyc for unboard!",
                    showConfirmButton: false,
                    timer: 1000,
                });
            });
            $(document).on('submit','.aadhar-otp-validate',function(e){
                e.preventDefault();
                var formdata = new FormData(this);
                formdata.append('_token','{{csrf_token()}}');
                formdata.append('txnid',txnid);
                disableBody();
                $.ajax({
                    url : "{{route('aadhar_verification_validate_otp')}}",
                    method : 'post',
                    data : formdata,
                    processData:false,
                    contentType:false,
                    cache:false,
                    success:function(data)
                    {
                        enableBody();
                        if(data.status == 'SUCCESS')
                        {
                            customMessageRedirect('success',data.message,"{{route('home')}}")
                        }
                        else
                        customMessage('error',data.message);
                    }
                })
            })
            $(document).on('submit','.aadhar-otp',function(e){
                e.preventDefault();
                var formdata = new FormData(this);
                formdata.append('_token','{{csrf_token()}}');
                disableBody();
                $.ajax({
                    url : "{{route('aadhar_verification_send_otp')}}",
                    method : 'post',
                    data : formdata,
                    processData:false,
                    contentType:false,
                    cache:false,
                    success:function(data)
                    {
                        enableBody();
                        if(data.status == 'SUCCESS')
                        {
                            $(".aadhar-otp").removeClass('aadhar-otp').addClass('aadhar-otp-validate');
                            $(".otp-section").show();
                            $("input[name='aadhar']").prop('disabled',true);
                            $(".form-button").text('Validate OTP');
                            txnid = data.reference;
                            customMessage('success',data.message);
                        }
                        else
                        {
                            customMessage('error',data.message);
                        }
                    }
                })
            })
            function disableBody() {
                $('body').addClass('disabled-body');
                $('<div class="spinner"></div>').appendTo('body');
              }
              function enableBody() {
                $('body').removeClass('disabled-body');
                $('.spinner').remove();
              }
              function customMessage(type,text)
              {
                  Swal.fire({
                    position: "center",
                    icon: type,
                    title: type,
                    text: text,
                    showConfirmButton: false,
                    timer: 1000,
                });
              }
              function customMessageRedirect(type,text,url)
              {
                  Swal.fire({
                    position: "center",
                    icon: type,
                    title: type,
                    text: text,
                    showConfirmButton: true,
                    // timer: 1000,
                }).then((result) => {
                if (result.isConfirmed) {
                    location.href = url;
                }
            });
              }
        </script>
    </body>
</html>
