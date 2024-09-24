<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/form-wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:32 GMT -->
<head>
    <!-- --------------------------------------------------- -->
    <!-- Title -->
    <!-- --------------------------------------------------- -->
    <title>{{$title}}</title>
    <!-- --------------------------------------------------- -->
    <!-- Required Meta Tag -->
    <!-- --------------------------------------------------- -->
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
    <link rel="stylesheet" href="{{url('assets')}}/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">
    
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="{{url('assets')}}/dist/css/style.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      padding: 20px;
    }
    
    .container {
      background-color: #fff;
      border-radius: 4px;
      padding: 20px;
      max-width: 400px;
      margin: 0 auto;
    }
    
    h1 {
      text-align: center;
      color: #333;
    }
    
    p {
      margin-bottom: 20px;
    }
    
    .status {
      text-align: center;
      font-size: 24px;
      color: #f44336;
      margin-bottom: 40px;
    }
    
    .contact-info {
      text-align: center;
    }
    
    .logo {
      display: block;
      margin: 0 auto;
      width: 200px;
      height: auto;
      margin-bottom: 20px;
    }
  </style>
</head>
  <body>
    <!-- Preloader -->
    <div class="preloader">
      <!--<img src="{{url('assets')}}/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />-->
      <img src="{{$logo}}" alt="loader" class="lds-ripple img-fluid" />
      
    </div>
    <!-- Preloader -->
    <div class="preloader">
      <!--<img src="{{url('assets')}}/dist/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />-->
      <img src="{{$logo}}" alt="loader" class="lds-ripple img-fluid" />
    </div>
    
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme"  data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
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
                    <img src="{{url('assets')}}/images/pending-kyc.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="container">
    <h1>KYC Pending</h1>
    <img src="{{$logo}}" alt="Company Logo" class="logo">
    <p class="status">Your KYC verification is pending.</p>
    <p>Please wait while we review your submitted documents. We will notify you once the verification process is completed.</p>
    <div class="contact-info">
      <p>If you have any questions or need assistance, please contact our support team:</p>
      <p>Email: {{$supportEmail}}</p>
      <p>Phone: {{$supportContact}}</p>
    </div>
  </div>
          
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
    <!--<script src="{{url('assets')}}/chart/realtime.js?v=1.2.1"></script>-->
    @if(Request::is('/'))
    <script src="{{ url('assets') }}/chart/realtime.js?v=1.2.1"></script>
    @endif
  </body>

<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/form-wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:32 GMT -->
</html>