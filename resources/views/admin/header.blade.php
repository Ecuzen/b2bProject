<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{$title}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ $fav }}">

    <!-- jvectormap -->
    <link href="{{url('assets')}}/dashboard/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- App css -->
    <link href="{{url('assets')}}/dashboard/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- DataTables -->
        
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bulma.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bulma.min.css">

    <link href="{{url('assets')}}/dashboard/plugins/datatables/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/plugins/datatables/buttons.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="{{url('assets')}}/dashboard/plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.4/dist/sweetalert2.min.js"></script>
    
    

    
    <style>
        .page-wrapper .page-content{
            background: #F5EBEB;
        }
        .left-sidenav-menu li>a{
            color: #000;
            font-weight: bold;
        }
        .navbar-custom{
            background: #151747!important;
        }
        .navbar-custom .nav-link{
            color: #fff !important;
        }
        .btn-soft-primary{
            color: #fff !important;
            font-weight: bold !important;
        }
        .btn-soft-primary{
            background-color: rgba(247,47,32,1)!important;
        }
        .navbar-custom .nav-link{
            font-weight: bold !important;
        }
        .left-sidenav-menu li{
            padding: 3px 5px;
        }
        .left-sidenav-menu ul .active{
            background: #151747;
            border-radius: 5px;
        }
        .left-sidenav-menu li>a.active .menu-icon{
            color: #fff !important;
        }
        .left-sidenav-menu ul li>a.active span{
            color: #fff;
        }
        .left-sidenav-menu li>a:hover{
            color: #000 !important;
        }
        .report-main-icon img{
            width: 80px;
        }
        .report-card .card-body{
            padding: 25px 20px;
        }
        .left-sidenav-menu li.mm-active .nav-item.active a.nav-link.active{
            color: #fff !important;
        }
        .left-sidenav-menu li.mm-active .mm-active .mm-show li a.active:hover{
            color: #fff !important;
        }
        .left-sidenav-menu li.mm-active .mm-active>a.active{
            color: #f72f20 !important;
        }
        .left-sidenav-menu ul .active ul li a:hover{
            color: #fff !important;
        }
        .left-sidenav-menu li.mm-active .mm-active .mm-show li a.active{
            color: #fff!important;
        }
        .left-sidenav-menu li.mm-active .mm-active>a i{
            color: #fff !important;
        }
    </style>
    
</head>

<body class="">
    <!-- Left Sidenav -->
    <div class="left-sidenav">
        <!-- LOGO -->
        <div class="brand">
            <a href="/admin" class="logo">

                <span>
                    <img src="{{ $logo }}" alt="logo-large" class="logo-lg logo-light">
                    <img src="{{ $logo }}" alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>
        <!--end logo-->
        <div class="menu-content h-100" data-simplebar>
            <ul class="metismenu left-sidenav-menu">
                <li class="menu-label mt-0">Main</li>
                <li>
                    <a href="/admin"> <i data-feather="home"
                            class="align-self-center menu-icon"></i><span>Dashboard</span></a>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-users-line"></i><span>Members</span><span
                            class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/admin-add-user"><i
                                    class="ti-control-record"></i>Add Member</a></li>
                        <li>
                            <a href="javascript: void(0);"><i class="ti-control-record"></i>List Member <span
                                    class="menu-arrow left-has-menu"><i class="mdi mdi-chevron-right"></i></span></a>
                            <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="/fetch-list-member/1">State Head</a></li>
                                <li><a href="/fetch-list-member/2">Master Distributor</a></li>
                                <li><a href="/fetch-list-member/3">Distributor</a></li>
                                <li><a href="/fetch-list-member/4">Retailer</a></li>
                                <li><a href="/fetch-list-member/0">All Member</a></li>
                            </ul>
                        </li>

                        <li class="nav-item"><a class="nav-link" href="/user-request"><i
                                    class="ti-control-record"></i>User Request</a></li>

                        <li class="nav-item"><a class="nav-link" href="/upgrade-member"><i
                                    class="ti-control-record"></i>Upgrad Member</a></li>
                        <!--<li class="nav-item"><a class="nav-link" href="/user-tickets/all"><i-->
                        <!--            class="ti-control-record"></i>User Complaints</a></li>-->

                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-fingerprint"></i><span>KYC Request</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/user-pending-kyc/pending"><i
                                    class="ti-control-record"></i>Pending Profile KYC</a></li>
                        <li class="nav-item"><a class="nav-link" href="/user-pending-kyc/all"><i
                                    class="ti-control-record"></i>Profile KYC</a></li>
                        <li class="nav-item"><a class="nav-link" href="/users-aeps-kyc"><i
                                    class="ti-control-record"></i>Aeps KYC</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-money-bill-transfer"></i><span>Payout</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/payout-account-request/all"><i
                                    class="ti-control-record"></i>Account Approval Request</a></li>
                        <li class="nav-item"><a class="nav-link" href="/payout-account-request/pending"><i
                                    class="ti-control-record"></i>Pending Approval Request</a></li>
                    </ul>
                </li>
                
                
                
           <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-money-bill-transfer"></i><span>Accont Service</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                            @if(!empty($vs_services))
                			@foreach($vs_services as $service_key => $service_val)
                				@php
                					$service_id = $service_val->id; 
                				@endphp
                				<li class="nav-item" > 
                					<a  class="nav-link"  href="{{route('pservice_form.index',$service_id)}}">
                						<i class="bx bx-right-arrow-alt"></i> {{ $service_val->title ?? '' }}
                					</a>
                				</li>
                			@endforeach
                		@endif
                    </ul>
                </li>
                
                
                
                

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-wallet"></i><span>Wallet</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/wallet-ledger"><i
                                    class="ti-control-record"></i>Wallet</a></li>
                        <li class="nav-item"><a class="nav-link" href="/user-fund/credit"><i
                                    class="ti-control-record"></i>Credit Fund</a></li>
                        <li class="nav-item"><a class="nav-link" href="/user-fund/debit"><i
                                    class="ti-control-record"></i>Debit Fund</a></li>
                        <li class="nav-item"><a class="nav-link" href="/topup-requests/pending"><i
                                    class="ti-control-record"></i>Pending Fund Request</a></li>
                        <li class="nav-item"><a class="nav-link" href="/topup-requests/all"><i
                                    class="ti-control-record"></i>Fund Request</a></li>
                        <li class="nav-item"><a class="nav-link" href="/lock-user-amount"><i
                                    class="ti-control-record"></i>Lock Amount</a></li>
                        <li class="nav-item"><a class="nav-link" href="/release-lock-amount"><i
                                    class="ti-control-record"></i>Release Lock Amount</a></li>

                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-landmark"></i><span>Transactions</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/wallet"><i
                                    class="ti-control-record"></i>Wallet Transactions</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/aeps"><i
                                    class="ti-control-record"></i>AEPS ICICI</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/bbps"><i
                                    class="ti-control-record"></i>BBPS</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/recharge"><i
                                    class="ti-control-record"></i>Recharge</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/dmt"><i
                                    class="ti-control-record"></i>DMT</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/payout"><i
                                    class="ti-control-record"></i>Payout</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/payoutcall"><i
                        class="ti-control-record"></i>Payout Pending Txn</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/qtransfer"><i
                                    class="ti-control-record"></i>Quick Transfer</a></li>
                        <!--<li class="nav-item"><a class="nav-link" href="auth-500.html"><i-->
                        <!--            class="ti-control-record"></i>Pan Registration</a></li>-->
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/uti"><i
                                    class="ti-control-record"></i>Pan Coupon</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-fetch-transactions/wallet-to-wallet"><i
                                    class="ti-control-record"></i>Wallet to Wallet</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-headset"></i><span>Support Ticket</span><span
                            class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/user-tickets/pending"><i
                                    class="ti-control-record"></i>Pending Support Ticket</a></li>
                        <li class="nav-item"><a class="nav-link" href="/user-tickets/all"><i
                                    class="ti-control-record"></i>Support Ticket</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-gear"></i><span>Settings</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/manage-settings"><i
                                    class="ti-control-record"></i>Main Settings</a></li>
                        <li class="nav-item"><a class="nav-link" href="/manage-package"><i
                                    class="ti-control-record"></i>Package</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-package-commission"><i
                                    class="ti-control-record"></i>Commission and Charges</a></li>
                        <!--<li class="nav-item"><a class="nav-link" href="auth-register.html"><i-->
                        <!--            class="ti-control-record"></i>Update Social Media Links</a></li>-->
                        <!--<li class="nav-item"><a class="nav-link" href="#"><i-->
                        <!--            class="ti-control-record"></i>Registration Fees</a></li>-->
                        <li class="nav-item"><a class="nav-link" href="/login-logs/user"><i
                                    class="ti-control-record"></i>User Login Logs</a></li>
                        <li class="nav-item"><a class="nav-link" href="/login-logs/admin"><i
                                    class="ti-control-record"></i>Admin Login Logs</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-user-tie"></i><span>Other Services</span><span
                            class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/admin-other-services/add"><i
                                    class="ti-control-record"></i>Add Service</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-other-services/view-all"><i
                                    class="ti-control-record"></i>List Service</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-people-roof"></i><span>Manage</span><span class="menu-arrow"><i
                                class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/admin-add-company-account"><i
                                    class="ti-control-record"></i>Company banks</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-manage-services"><i
                                    class="ti-control-record"></i>Manage Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{route('notification.index')}}"><i
                                    class="ti-control-record"></i>Notifications</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);"><i class="fa-solid fa-user-gear"></i><span>Employee Management</span><span
                            class="menu-arrow"><i class="mdi mdi-chevron-right"></i></span></a>
                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="nav-item"><a class="nav-link" href="/admin-add-employee"><i
                                    class="ti-control-record"></i>Add Employee</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin-manage-all-employee"><i
                                    class="ti-control-record"></i>View Employee</a></li>
                    </ul>
                </li>

                <hr class="hr-dashed hr-menu">
            </ul>

            
        </div>
    </div>
    <!-- end left-sidenav-->

    <div class="page-wrapper">
        <!-- Top Bar Start -->
        <div class="topbar">
            <!-- Navbar -->
            <nav class="navbar-custom">
                <ul class="list-unstyled topbar-nav float-end mb-0">
                    <li class="dropdown hide-phone">
                        <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            <i data-feather="search" class="topbar-icon"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end dropdown-lg p-0">
                            <!-- Top Search Bar -->
                            <div class="app-search-topbar">
                                <form action="#" method="get">
                                    <input type="search" name="search" class="from-control top-search mb-0"
                                        placeholder="Type text...">
                                    <button type="submit"><i class="ti-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown hide-phone">
                        <a class="nav-link dropdown-toggle arrow-none waves-light waves-effect">
                            <i  class="ti-wallet topbar-icon"></i>  {{$totalUserAmount}}
                        </a>
                    </li>



                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="ms-1 nav-user-name hidden-sm">{{$adminName}}</span>
                            <img src="{{url('assets')}}/dashboard/assets/images/users/user-5.jpg" alt="profile-user"
                                class="rounded-circle thumb-xs" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!--<a class="dropdown-item" href="pages-profile.html"><i data-feather="user"-->
                            <!--        class="align-self-center icon-xs icon-dual me-1"></i> Profile</a>-->
                            <a class="dropdown-item" href="/admin-profile-settings"><i data-feather="settings"
                                    class="align-self-center icon-xs icon-dual me-1"></i> Change Password</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a class="dropdown-item" href="/admin-logout"><i data-feather="power"
                                    class="align-self-center icon-xs icon-dual me-1"></i> Logout</a>
                        </div>
                    </li>
                </ul><!--end topbar-nav-->

                <ul class="list-unstyled topbar-nav mb-0">
                    <li>
                        <button class="nav-link button-menu-mobile">
                            <i data-feather="menu" class="align-self-center topbar-icon"></i>
                        </button>
                    </li>
                    
                </ul>
            </nav>
            <!-- end navbar-->
        </div>
        <!-- Top Bar End -->

        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-title-box">
                            <div class="row">
                                <div class="col">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{$dName}}</a></li>
                                        <li class="breadcrumb-item active">{{$tname}}</li>
                                    </ol>
                                </div><!--end col-->

                            </div><!--end row-->
                        </div><!--end page-title-box-->
                    </div><!--end col-->
                </div><!--end row-->
                <!-- end page title end breadcrumb -->