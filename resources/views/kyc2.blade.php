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
                    <img src="{{url('assets')}}/images/kyc-logo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <section>
            <div class="row">
              <div class="col-12">
                <!-- ---------------------
                                                    start Step wizard with validation
                                                ---------------- -->
                <div class="card">
                  <div class="card-body wizard-content">
                    <h4 class="card-title">Complete process for unboard</h4>
                    <p class="card-subtitle mb-3"> We never share your documents with any one </p>
                    <form action="#" class="tab-wizard wizard-circle mt-5" enctype="multipart/form-data">
                      <!-- Step 1 -->
                      <h6>Basic Information</h6>
                      <section>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="firstname"> First Name : <span class="danger text-danger">*</span>
                              </label>
                              <input type="text" class="form-control required" id="firstname" name="firstname" value="{{$firstname}}" />
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="lastname"> Last Name : <span class="danger text-danger">*</span>
                              </label>
                              <input type="text" class="form-control required" id="lastname" name="lastname" value="{{$lastname}}"/>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="emailaddress"> Email Address : <span class="danger text-danger">*</span>
                              </label>
                              <input type="email" class="form-control required" id="emailaddress" name="emailaddress" value="{{$email}}"/>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="phone">Phone Number :<span class="danger text-danger">*</span></label>
                              <input type="tel" class="form-control required" id="phone" value="{{$phone}}"/>
                            </div>
                          </div>
                        </div>
                      </section>
                      <!-- Step 2 -->
                      <h6>Personal Information</h6>
                      <section>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                  <label for="fatherName">Father Name :</label>
                                  <input type="text" class="form-control required" id="fatherName" name="fatherName"/>
                                </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="aadharNumber">Aadhar Number :</label>
                              <input type="text" class="form-control required" id="aadharNumber" name="aadharNumber" maxlength="12"/>
                            </div>
                          </div>
                          
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="aadharFront">Aadhar Image(front) :</label>
                              <input type="file" class="form-control required" id="aadharFront" name="aadharFront" accept="image/*"/>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="aadharBack">Aadhar Image(back) :</label>
                              <input type="file" class="form-control required" id="aadharBack" name="aadharBack" accept="image/*"/>
                            </div>
                          </div>
                          
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="panNumber">PAN Number :</label>
                              <input type="text" class="form-control required text-uppercase" id="panNumber" name="panNumber" maxlength="10" />
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="panImage">PAN Image :</label>
                              <input type="file" class="form-control required" id="panImage" name="panImage" accept="image/*"/>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="address">Address :</label>
                              <textarea name="address" id="address" rows="6" class="form-control required"></textarea>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="dob">Date of birth :</label>
                              <input type="date" class="form-control required" id="dob" name="dob" accept="image/*"/>
                            </div>
                          </div>
                        </div>
                      </section>
                      <!-- Step 3 -->
                      <h6>Shop Details</h6>
                      <section>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label for="shopName">Shop Name :</label>
                              <input type="text" class="form-control required" id="shopName" name="shopName" />
                            </div>
                            <div class="mb-3">
                              <label for="pinCode">PIN Code :</label>
                              <input type="text" class="form-control required" id="pinCode" name="pinCode" />
                            </div>
                            <div class="mb-3">
                              <label for="state">State :</label>
                              <select class="form-select required" id="state" name="state">
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                <option value="{{$state->code}}">{{$state->name}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                          @csrf
                          <input type="hidden" value="{{csrf_token()}}"  name="_token">
                          <div class="col-md-6">
                              <div class="mb-3">
                              <label>District :</label>
                              <input type="text" class="form-control required" id="district" name="district" />
                            </div>
                            <div class="mb-3">
                              <label for="shopAddress">Shop Address</label>
                              <textarea name="shopAddress" id="shopAddress" rows="4" class="form-control required"></textarea>
                            </div>
                          </div>
                        </div>
                      </section>
                      <!-- Step 4 -->
                      <h6>Step 4</h6>
                      <section>
                        
                      </section>
                    </form>
                  </div>
                </div>
                <!-- ---------------------
                                                    end Step wizard with validation
                                                ---------------- -->
              </div>
            </div>
          </section>
          <!-- --------------------------------------------------- -->
          <!--  Form Wizard End -->
          <!-- --------------------------------------------------- -->
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
    
    <script>
        $(document).ready(function(){
            
            Swal.fire({
      position: 'center',
      icon: 'info',
      title: 'Information',
        text : 'Please complete your kyc for unboard!',
      showConfirmButton: false,
      timer: 2000
    })
        })
    </script>
  </body>

<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/form-wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:32 GMT -->
</html>