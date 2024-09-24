@include('admin.header')
<div class="row">
                    <div class="col-lg-12">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">AEPS Transactions</p>
                                                <h3 class="m-0">&#8377; {{$todayAeps}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/aeps.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">BBPS</p>
                                                <h3 class="m-0">&#8377; {{$todayBbps}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/bbps_report.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">DMT</p>
                                                <h3 class="m-0">&#8377; {{$todayDmt}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/dmt.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">PAYOUT</p>
                                                <h3 class="m-0">&#8377; {{$todayPayout}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/payout.png">
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->

                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Mobile Recharge</p>
                                                <h3 class="m-0">&#8377; {{$todayMobile}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/mobile-prepaid.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">DTH Recharge</p>
                                                <h3 class="m-0">&#8377; {{$todayDth}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/dth_recharge.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">UTI</p>
                                                <h3 class="m-0">&#8377; {{$todayUti}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/pan_report.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">QUCK TRANSFER </p>
                                                <h3 class="m-0">&#8377; {{$todayQt}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                   <img src="{{url('assets')}}/dist/images/admin-icon/quick_transfer.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->


                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Total Users</p>
                                                <h3 class="m-0">{{$totalUsers}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/user-02.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Active Users</p>
                                                <h3 class="m-0">{{$activeUsers}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                     <img src="{{url('assets')}}/dist/images/admin-icon/user.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Deactive Users</p>
                                                <h3 class="m-0">{{$deactiveUsers}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/deactivate-user-02.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Today Commission</p>
                                                <h3 class="m-0">&#8377; {{$todayCommission}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/comission.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->

                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Monthly Commission</p>
                                                <h3 class="m-0">&#8377; {{$monthlyCommission}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/comission-02.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Total Wallet</p>
                                                <h3 class="m-0">&#8377; {{$totalUserWallet}}</h3>
                                               
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/wallet.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Aeps Done Users</p>
                                                <h3 class="m-0">{{$aepsDone}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                    <img src="{{url('assets')}}/dist/images/admin-icon/aeps-03.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->

                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Aeps Not Done Users</p>
                                                <h3 class="m-0">{{$aepsNotDone}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                     <img src="{{url('assets')}}/dist/images/admin-icon/aeps-02.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->
                            <!--<div class="col-md-6 col-lg-3">-->
                            <!--    <div class="card report-card">-->
                            <!--        <div class="card-body">-->
                            <!--            <div class="row d-flex justify-content-center">-->
                            <!--                <div class="col">-->
                            <!--                    <p class="text-dark mb-0 fw-semibold">Today Wallet to Wallet</p>-->
                            <!--                    <h3 class="m-0">&#8377; {{$todayBbps}}</h3>-->
                            <!--                    <p class="mb-0 text-truncate text-muted"><span class="text-success"><i-->
                            <!--                                class="mdi mdi-trending-up"></i>10.5%</span> Completions-->
                            <!--                        Weekly</p>-->
                            <!--                </div>-->
                            <!--                <div class="col-auto align-self-center">-->
                            <!--                    <div class="report-main-icon bg-light-alt">-->
                            <!--                        <i data-feather="briefcase"-->
                            <!--                            class="align-self-center text-muted icon-sm"></i>-->
                            <!--                    </div>-->
                            <!--                </div>-->
                            <!--            </div>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Today Qt charges</p>
                                                <h3 class="m-0">&#8377; {{$todayQtCharge}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                   <img src="{{url('assets')}}/dist/images/admin-icon/qt-charges.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div> <!--end col-->

                            <div class="col-md-6 col-lg-3">
                                <div class="card report-card">
                                    <div class="card-body">
                                        <div class="row d-flex justify-content-center">
                                            <div class="col">
                                                <p class="text-dark mb-0 fw-semibold">Monthly Qt charge</p>
                                                <h3 class="m-0">&#8377; {{$monthlyQtCharge}}</h3>
                                                
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="report-main-icon bg-light-alt">
                                                   <img src="{{url('assets')}}/dist/images/admin-icon/montly-qt.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end card-body-->
                                </div><!--end card-->
                            </div>
                            <div class="col-lg-6"></div>
                        </div><!--end row-->

                    </div><!--end col-->

                </div>
@include('admin.footer')