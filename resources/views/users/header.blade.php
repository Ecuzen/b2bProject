<!DOCTYPE html>
<html lang="en">
  
<!-- Mirrored from demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/html/main/form-wizard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 22 May 2023 12:37:32 GMT -->
<head>
    <!-- ----------------------------------------------------->
    <!-- Title -->
    <!-- ----------------------------------------------------->
    <title>{{$title}}</title>
    <!-- ----------------------------------------------------->
    <!-- Required Meta Tag -->
    <!-- ----------------------------------------------------->
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
    <link rel="shortcut icon" type="image/png" href="{{$fav}}" />
    <!-- Owl Carousel  -->
    <link rel="stylesheet" href="{{url('assets')}}/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">
    
    <!-- Core Css -->
    <link  id="themeColors"  rel="stylesheet" href="{{url('assets')}}/dist/css/style.min.css" />
    <link rel="stylesheet" href="{{url('assets')}}/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<input type="hidden" id="latitude">
<input type="hidden" id="longitude">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

<style>
        /* Custom CSS for SweetAlert dialog */
        .custom-dialog-txn {
            width: 400px; /* Set desired width */
            height: 230px;
            
        }
        .preheader-wallet i{
            font-size: 24px;
            color: #151747;
        }
        .dash-icon img{
            width: 30px;
        }
        .dash-icon{
            background-color: #151747 !important;
        }
        .bg-light-red{
            background: rgba(247, 47, 32, 0.1);
        }
        .bg-light-blue{
            background: rgba(21, 23, 71, 0.1);
        }
        .bbps-icons{
            height: 90px;
            padding: 20px;
        }
        .bbps-icons img{
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .side-icons img{
            height: 21px;
        }
        .header-news{
            margin-top: 60px;
            background: rgba(247, 47, 32, 0.1);
            padding: 10px 0;
            color: #000;
            font-weight: bold;
            font-size: 18px;
        }
        .border-ecuzen{
            border: 1px solid #151747 !important;
        }
        .text-eczn-blue{
            color: #151747 !important;
        }
        .line-eczn{
            border-left: 1px solid #151747 !important;
        }
        .btn-blue{
            background: #151747 !important;
        }
        .box-shadow-1{
            box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px, rgb(51, 51, 51) 0px 0px 0px 3px;
        }
        .bg-light-info div img{
            filter: hue-rotate(45deg);
        }
        .dash-service{
            min-height: 160px !important;
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        }
        .dash-service:hover{
            box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        }
        .table-txn{
            overflow: scroll !important;
        }
        .table-txn::-webkit-scrollbar {
          display: none;
        }
        .btn:hover {
            color: #fff;
            background-color: #151747;
            border-color: #151747;
        }
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
            #extraServices{
                display: none;
            }
            .btnextraServices{
                position: relative;
                margin-bottom: 50px;
            }
            #viewextraServices{
                position: absolute;
                display: flex;
                left: 50%;
                transform: translate(-50%, 0);
                cursor: pointer;
                font-size: 16px;
                color: #151747;
            }
            #viewextraServices i{
                margin-left: 5px;
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
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme"  data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
      <!-- Sidebar Start -->
      <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
          <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{route('home')}}" class="text-nowrap logo-img">
              <img src="{{$logo}}" class="dark-logo" width="180" alt="" />
              <img src="{{$logo}}" class="light-logo"  width="180" alt="" />
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
          </div>
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
              <!-- ============================= -->
              <!-- Home -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <!-- =================== -->
              <!-- Dashboard -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('home')}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-aperture"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
              @if($userRole != 4)
              <li class="sidebar-item">
                <a class="sidebar-link" href="/addmember" aria-expanded="false">
                  <span>
                    <i class="fa-solid fa-user-plus"></i>
                  </span>
                  <span class="hide-menu">My Members</span>
                </a>
              </li>
              @endif
              <!--<li class="sidebar-item">-->
              <!--  <a class="sidebar-link" href="/addfund" aria-expanded="false">-->
              <!--    <span>-->
              <!--      <i class="fa-solid fa-wallet"></i>-->
              <!--    </span>-->
              <!--    <span class="hide-menu">Add Fund</span>-->
              <!--  </a>-->
              <!--</li>-->
              
               
              
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                  <span class="d-flex">
                    <i class="fa-solid fa-wallet"></i>
                  </span>
                  <span class="hide-menu">Add Fund</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="/addfund" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Add Fund by Bank</span>
                    </a>
                  </li>
                 <!-- <li class="sidebar-item">
                    <a href="{{route('addfundByPg')}}" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Add Fund by PG</span>
                    </a>
                  </li>-->
                </ul>
              </li>
              
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('walletTowallet')}}" aria-expanded="false">
                  <span>
                    <i class="fa-solid fa-wallet"></i>
                  </span>
                  <span class="hide-menu">Wallet to Wallet</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a class="sidebar-link" href="/commission-charges" aria-expanded="false">
                  <span>
                    <i class="fa-solid fa-gift"></i>
                  </span>
                  <span class="hide-menu">Commission Plan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('raiseTicket')}}" aria-expanded="false">
                  <span>
                    <i class="fa fa-ticket"></i>
                  </span>
                  <span class="hide-menu">Support Ticket</span>
                </a>
              </li>
              
              <!-- ============================= -->
              <!-- ALL API -->
              <!-- ============================= -->
              
                <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                  <span class="d-flex">
                    <i class="ti ti-device-mobile-bolt"></i>
                  </span>
                  <span class="hide-menu">Accont Service</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  	@if(!empty($vs_services))
                			@foreach($vs_services as $service_key => $service_val)
                				@php
                					$service_id = $service_val->id; 
                				@endphp
              
                				<li class="sidebar-item"> 
                					<a class="sidebar-link" href="{{route('service_form.index',$service_id)}}" class="class="sidebar-link"">
                					     <div class="round-16 d-flex align-items-center justify-content-center">
                                        <i class="ti ti-circle"></i>
                                      </div>
                					    
                						<span class="hide-menu"> {{ $service_val->title ?? '' }}
                					</a>
                				</li>
                			@endforeach
                		@endif
                </ul>
              </li>
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">ALL SERVICES</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/aeps" aria-expanded="false">
                  <span>
                    <i class="ti ti-coins"></i>
                  </span>
                  <span class="hide-menu">Aeps</span>
                </a>
              </li>
              <!--<li class="sidebar-item">
                <a class="sidebar-link" href="/bbps-index" aria-expanded="false">
                  <span>
                    <i class="ti ti-list-details"></i>
                  </span>
                  <span class="hide-menu">Bbps</span>
                </a>
              </li>-->
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('bbps.index')}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-list-details"></i>
                  </span>
                  <span class="hide-menu">Bbps New</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/money-transfer" aria-expanded="false">
                  <span>
                    <i class="ti ti-cash-banknote"></i>
                  </span>
                  <span class="hide-menu">Money Transfer</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/quick-transfer" aria-expanded="false">
                  <span>
                    <i class="fa-solid fa-money-bill-transfer"></i>
                  </span>
                  <span class="hide-menu">Quick transfer</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/payout" aria-expanded="false">
                  <span>
                    <i class="ti ti-notes"></i>
                  </span>
                  <span class="hide-menu">Payout</span>
                </a>
              </li>
              
             
              <!--<li class="sidebar-item">
                <a class="sidebar-link" href="{{route('subscription.index',['credit_card'])}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-credit-card-filled"></i>
                  </span>
                  <span class="hide-menu">Credit card</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('subscription.index',['fastag'])}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-car-crane"></i>
                  </span>
                  <span class="hide-menu">FASTag</span>
                </a>
              </li>-->
               <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                  <span class="d-flex">
                    <i class="ti ti-device-mobile-bolt"></i>
                  </span>
                  <span class="hide-menu">Recharge</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="/recharge-mobile" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Mobile Recharge</span>
                    </a>
                  </li>
                 <!-- <li class="sidebar-item">
                    <a href="recharge-dth" class="sidebar-link">
                      <div class="round-16 d-flex align-items-center justify-content-center">
                        <i class="ti ti-circle"></i>
                      </div>
                      <span class="hide-menu">Dth Recharge</span>
                    </a>
                  </li>-->
                </ul>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/psa" aria-expanded="false">
                  <span>
                    <i class="ti ti-file-text"></i>
                  </span>
                  <span class="hide-menu">Uti Coupon</span>
                </a>
              </li>
             <li class="sidebar-item">
                <a class="sidebar-link" href="/other-services" aria-expanded="false">
                  <span>
                     <i class="ti ti-24-hours"></i>
                  </span>
                  <span class="hide-menu">Other Services</span>
                </a>
              </li>
             <!--<li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Other Services</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{route('user.pan_verification')}}" aria-expanded="false">
                  <span class="side-icons">
                    <i class="ti ti-id-badge-2"></i>
                  </span>
                  <span class="hide-menu">PAN Verification</span>
                </a>
              </li>-->
              <!--ALL API HISTORY-->
              
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Transaction History</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/aeps" aria-expanded="false">
                  <span class="side-icons">
                    <i class="fa-solid fa-fingerprint"></i>
                  </span>
                  <span class="hide-menu">Aeps</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/money-transfer" aria-expanded="false">
                  <span class="side-icons">
                   <i class="fa-solid fa-arrow-right-arrow-left"></i>
                  </span>
                  <span class="hide-menu">Money Transfer</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/qtransfer" aria-expanded="false">
                  <span class="side-icons">
                    <i class="bi bi-bank2"></i>
                  </span>
                  <span class="hide-menu">Qtransfer</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/payout" aria-expanded="false">
                  <span class="side-icons">
                    <i class="bi bi-currency-rupee"></i>
                  </span>
                  <span class="hide-menu">Payout</span>
                </a>
              </li>
               <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/recharge" aria-expanded="false">
                  <span class="side-icons">
                    <i class="fa-solid fa-mobile-button"></i>
                  </span>
                  <span class="hide-menu">Recharge</span>
                </a>
              </li>
               <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/bbps" aria-expanded="false">
                  <span class="side-icons">
                    <i class="bi bi-receipt-cutoff"></i>
                  </span>
                  <span class="hide-menu">Bbps</span>
                </a>
              </li>
               <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/uti-coupan" aria-expanded="false">
                  <span class="side-icons">
                    <i class="bi bi-credit-card-2-front"></i>
                  </span>
                  <span class="hide-menu">Uti Coupon</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/wallet" aria-expanded="false">
                  <span class="side-icons">
                    <i class="fa-solid fa-wallet"></i>
                  </span>
                  <span class="hide-menu">Wallet</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="/transactions/wallet-to-wallet" aria-expanded="false">
                  <span class="side-icons">
                    <i class="fa-solid fa-wallet"></i>
                  </span>
                  <span class="hide-menu">Wallet To Wallet</span>
                </a>
              </li>
              
             </ul>
             
             
             
            <!--<div class="unlimited-access hide-menu bg-light-primary position-relative my-7 rounded">-->
            <!--  <div class="d-flex">-->
            <!--    <div class="unlimited-access-title">-->
            <!--      <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Unlimited Access</h6>-->
            <!--      <button class="btn btn-primary fs-2 fw-semibold lh-sm">Signup</button>-->
            <!--    </div>-->
            <!--    <div class="unlimited-access-img">-->
            <!--      <img src="{{url('assets')}}/dist/images/backgrounds/rocket.png" alt="" class="img-fluid">-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
          </nav>
          <div class="fixed-profile p-3 bg-light-secondary rounded sidebar-ad mt-3">
            <div class="hstack gap-3">
              <div class="john-img">
                <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" class="rounded-circle" width="40" height="40" alt="">
              </div>
              <div class="john-title">
                <h6 class="mb-0 fs-4 fw-semibold">Mathew</h6>
                <span class="fs-2 text-dark">Designer</span>
              </div>
              <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="button" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
                <i class="ti ti-power fs-6"></i>
              </button>
            </div>
          </div>  
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>
      <!-- --------------------------------------------------- -->
      <!-- Main Wrapper -->
      <!-- --------------------------------------------------- -->
      <div class="body-wrapper">
        <!-- --------------------------------------------------- -->
        <!-- Header Start -->
        <!-- --------------------------------------------------- -->
        <header class="app-header"> 
          <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                  <i class="ti ti-menu-2"></i>
                </a>
              </li>
              <!--<li class="nav-item d-none d-lg-block">-->
              <!--  <a class="nav-link nav-icon-hover" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">-->
              <!--    <i class="ti ti-search"></i>-->
              <!--  </a>-->
              <!--</li>-->
            </ul>
            <!--<ul class="navbar-nav quick-links d-none d-lg-flex">-->
            <!--  <li class="nav-item dropdown hover-dd d-none d-lg-block">-->
            <!--    <a class="nav-link" href="javascript:void(0)" data-bs-toggle="dropdown">Apps<span class="mt-1"><i class="ti ti-chevron-down"></i></span></a>-->
            <!--    <div class="dropdown-menu dropdown-menu-nav dropdown-menu-animate-up py-0">-->
            <!--      <div class="row">-->
            <!--        <div class="col-8">-->
            <!--          <div class=" ps-7 pt-7">-->
            <!--            <div class="border-bottom">-->
            <!--              <div class="row">-->
            <!--                <div class="col-6">-->
            <!--                  <div class="position-relative">-->
            <!--                    <a href="app-chat.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-chat.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Chat Application</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">New messages arrived</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-invoice.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-invoice.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Invoice App</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">Get latest invoice</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-contact2.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-mobile.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Contact Application</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">2 Unsaved Contacts</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-email.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-message-box.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Email App</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">Get new emails</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                  </div>-->
            <!--                </div>-->
            <!--                <div class="col-6">-->
            <!--                  <div class="position-relative">-->
            <!--                    <a href="page-user-profile.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-cart.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">User Profile</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">learn more information</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-calendar.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-date.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Calendar App</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">Get dates</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-contact.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-lifebuoy.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Contact List Table</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">Add new contact</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                    <a href="app-notes.html" class="d-flex align-items-center pb-9 position-relative">-->
            <!--                      <div class="bg-light rounded-1 me-3 p-6 d-flex align-items-center justify-content-center">-->
            <!--                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-dd-application.svg" alt="" class="img-fluid" width="24" height="24">-->
            <!--                      </div>-->
            <!--                      <div class="d-inline-block">-->
            <!--                        <h6 class="mb-1 fw-semibold bg-hover-primary">Notes Application</h6>-->
            <!--                        <span class="fs-2 d-block text-dark">To-do and Daily tasks</span>-->
            <!--                      </div>-->
            <!--                    </a>-->
            <!--                  </div>-->
            <!--                </div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--            <div class="row align-items-center py-3">-->
            <!--              <div class="col-8">-->
            <!--                <a class="fw-semibold text-dark d-flex align-items-center lh-1" href="#"><i class="ti ti-help fs-6 me-2"></i>Frequently Asked Questions</a>-->
            <!--              </div>-->
            <!--              <div class="col-4">-->
            <!--                <div class="d-flex justify-content-end pe-4">-->
            <!--                  <button class="btn btn-primary">Check</button>-->
            <!--                </div>-->
            <!--              </div>-->
            <!--            </div>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--        <div class="col-4 ms-n4">-->
            <!--          <div class="position-relative p-7 border-start h-100">-->
            <!--            <h5 class="fs-5 mb-9 fw-semibold">Quick Links</h5>-->
            <!--            <ul class="">-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="page-pricing.html">Pricing Page</a>-->
            <!--              </li>-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="/user-logout">Authentication Design</a>-->
            <!--              </li>-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="authentication-register.html">Register Now</a>-->
            <!--              </li>-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="authentication-error.html">404 Error Page</a>-->
            <!--              </li>-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="app-notes.html">Notes App</a>-->
            <!--              </li>-->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="page-user-profile.html">User Application</a>-->
            <!--              </li>   -->
            <!--              <li class="mb-3">-->
            <!--                <a class="fw-semibold text-dark bg-hover-primary" href="page-account-settings.html">Account Settings</a>-->
            <!--              </li>-->
            <!--            </ul>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </li>-->
            <!--  <li class="nav-item dropdown-hover d-none d-lg-block">-->
            <!--    <a class="nav-link" href="app-chat.html">Chat</a>-->
            <!--  </li>-->
            <!--  <li class="nav-item dropdown-hover d-none d-lg-block">-->
            <!--    <a class="nav-link" href="app-calendar.html">Calendar</a>-->
            <!--  </li>-->
            <!--  <li class="nav-item dropdown-hover d-none d-lg-block">-->
            <!--    <a class="nav-link" href="app-email.html">Email</a>-->
            <!--  </li>-->
            <!--</ul>-->
            <div class="d-block d-lg-none">
              <img src="{{$logo}}" class="dark-logo" width="180" alt="" />
              <img src="{{$logo}}" class="light-logo"  width="180" alt="" />
            </div>
            <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
              </span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
              <div class="d-flex align-items-center justify-content-between">
                <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar" aria-controls="offcanvasWithBothOptions">
                  <i class="ti ti-align-justified fs-7"></i>
                </a>
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
                  <!--<li class="nav-item dropdown">-->
                  <!--  <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">-->
                  <!--    <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-en.svg" alt="" class="rounded-circle object-fit-cover round-20">-->
                  <!--  </a>-->
                  <!--  <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">-->
                  <!--    <div class="message-body" data-simplebar>-->
                  <!--      <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">-->
                  <!--        <div class="position-relative">-->
                  <!--          <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-en.svg" alt="" class="rounded-circle object-fit-cover round-20">-->
                  <!--        </div>-->
                  <!--        <p class="mb-0 fs-3">English (UK)</p>-->
                  <!--      </a>-->
                  <!--      <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">-->
                  <!--        <div class="position-relative">-->
                  <!--          <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-cn.svg" alt="" class="rounded-circle object-fit-cover round-20">-->
                  <!--        </div>-->
                  <!--        <p class="mb-0 fs-3">中国人 (Chinese)</p>-->
                  <!--      </a>-->
                  <!--      <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">-->
                  <!--        <div class="position-relative">-->
                  <!--          <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-fr.svg" alt="" class="rounded-circle object-fit-cover round-20">-->
                  <!--        </div>-->
                  <!--        <p class="mb-0 fs-3">français (French)</p>-->
                  <!--      </a>-->
                  <!--      <a href="javascript:void(0)" class="d-flex align-items-center gap-2 py-3 px-4 dropdown-item">-->
                  <!--        <div class="position-relative">-->
                  <!--          <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-sa.svg" alt="" class="rounded-circle object-fit-cover round-20">-->
                  <!--        </div>-->
                  <!--        <p class="mb-0 fs-3">عربي (Arabic)</p>-->
                  <!--      </a>-->
                  <!--    </div>-->
                  <!--  </div>-->
                  <!--</li>-->
                  <!--<li class="nav-item">-->
                  <!--  <a class="nav-link notify-badge nav-icon-hover" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">-->
                  <!--      <i class="ti ti-basket"></i>-->
                  <!--      <span class="badge rounded-pill bg-danger fs-2">2</span>                   -->
                  <!--  </a>-->
                  <!--</li>-->
                  
                  <li class="nav-item dropdown d-flex align-items-center preheader-wallet">
                    <i class="fa-solid fa-wallet"></i>
                    <p class="m-0 ms-2">Rs. {{round($walletBalance,2)}}</p>
                  </li>
                  <li class="nav-item dropdown me-2">
                    <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="ti ti-bell-ringing"></i>
                      <div class="notification bg-primary rounded-circle"></div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                        <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 new</span>
                      </div>
                      <div class="message-body" data-simplebar>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                            <span class="d-block">Congratulate him</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-2.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">New message</h6>
                            <span class="d-block">Salma sent you new message</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-3.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">Bianca sent payment</h6>
                            <span class="d-block">Check your earnings</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-4.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">Jolly completed tasks</h6>
                            <span class="d-block">Assign her new tasks</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-5.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">John received payment</h6>
                            <span class="d-block">$230 deducted from account</span>
                          </div>
                        </a>
                        <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                          <span class="me-3">
                            <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                          </span>
                          <div class="w-75 d-inline-block v-middle">
                            <h6 class="mb-1 fw-semibold">Roman Joined the Team!</h6>
                            <span class="d-block">Congratulate him</span>
                          </div>
                        </a>
                      </div>
                      <div class="py-6 px-7 mb-1">
                        <button class="btn btn-outline-primary w-100"> See All Notifications </button>
                      </div>
                    </div>
                  </li>
                  
                  <li class="nav-item dropdown">
                    <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                      <div class="d-flex align-items-center">
                        <div class="user-profile-img">
                          <img src="{{$profileImage}}" class="rounded-circle" width="35" height="35" alt="" />
                        </div>
                      </div>
                    </a>
                    <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                      <div class="profile-dropdown position-relative" data-simplebar>
                        <div class="py-3 px-7 pb-0">
                          <h5 class="mb-0 fs-5 fw-semibold">User Profile</h5>
                        </div>
                        <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                          <img src="{{$profileImage}}" class="rounded-circle" width="80" height="80" alt="" />
                          <div class="ms-3">
                            <h5 class="mb-1 fs-3">{{$customerName}}</h5>
                            <span class="mb-1 d-block text-dark">{{$customerRole}}</span>
                            <p class="mb-0 d-flex text-dark align-items-center gap-2">
                              <i class="ti ti-mail fs-4"></i> {{$customerEmail}}
                            </p>
                          </div>
                        </div>
                        <div class="message-body">
                          <a href="{{route('userProfileSettings')}}" class="py-8 px-7 mt-8 d-flex align-items-center">
                            <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">
                              <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-account.svg" alt="" width="24" height="24">
                            </span>
                            <div class="w-75 d-inline-block v-middle ps-3">
                              <h6 class="mb-1 bg-hover-primary fw-semibold"> My Profile </h6>
                              <span class="d-block text-dark">Account Settings</span>
                            </div>
                          </a>
                          <!--<a href="app-email.html" class="py-8 px-7 d-flex align-items-center">-->
                          <!--  <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">-->
                          <!--    <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-inbox.svg" alt="" width="24" height="24">-->
                          <!--  </span>-->
                          <!--  <div class="w-75 d-inline-block v-middle ps-3">-->
                          <!--    <h6 class="mb-1 bg-hover-primary fw-semibold">My Inbox</h6>-->
                          <!--    <span class="d-block text-dark">Messages & Emails</span>-->
                          <!--  </div>-->
                          <!--</a>-->
                          <!--<a href="app-notes.html" class="py-8 px-7 d-flex align-items-center">-->
                          <!--  <span class="d-flex align-items-center justify-content-center bg-light rounded-1 p-6">-->
                          <!--    <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-tasks.svg" alt="" width="24" height="24">-->
                          <!--  </span>-->
                          <!--  <div class="w-75 d-inline-block v-middle ps-3">-->
                          <!--    <h6 class="mb-1 bg-hover-primary fw-semibold">My Task</h6>-->
                          <!--    <span class="d-block text-dark">To-do and Daily Tasks</span>-->
                          <!--  </div>-->
                          <!--</a>-->
                        </div>
                        <div class="d-grid py-4 px-7 pt-8">
                          <!--<div class="upgrade-plan bg-light-primary position-relative overflow-hidden rounded-4 p-4 mb-9">-->
                          <!--  <div class="row">-->
                          <!--    <div class="col-6">-->
                          <!--      <h5 class="fs-4 mb-3 w-50 fw-semibold text-dark">Unlimited Access</h5>-->
                          <!--      <button class="btn btn-primary text-white">Upgrade</button>-->
                          <!--    </div>-->
                          <!--    <div class="col-6">-->
                          <!--      <div class="m-n4">-->
                          <!--        <img src="{{url('assets')}}/dist/images/backgrounds/unlimited-bg.png" alt="" class="w-100">-->
                          <!--      </div>-->
                          <!--    </div>-->
                          <!--  </div>-->
                          <!--</div>-->
                          <a href="/user-logout" class="btn btn-outline-primary">Log Out</a>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
        </header>
        <!-- --------------------------------------------------- -->
        <!-- Header End -->
        <!-- --------------------------------------------------- -->