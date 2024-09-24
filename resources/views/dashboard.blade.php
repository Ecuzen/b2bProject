@include('users/header')
<div class="container-fluid">
    <div style="background: rgba(247, 47, 32, 0.1);padding: 0 15px;display: flex;" class="">
        <marquee style="background: none;" class="header-news mt-0 " style="border-radius: 7px;"  direction="left">
        {{$news}}
        </marquee>
    </div>
</div>
@if(session('success'))
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  {{session('success')}}
	  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif

@if(session('warning'))
	<div class="alert alert-warning alert-dismissible fade show" role="alert">
		{{session('warning')}}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif
@if(session('error'))
	<div class="alert alert-danger alert-dismissible fade show" role="alert">
		{{session('error')}}
		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
@endif
<div class="container-fluid pt-2">
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
              <div class="card w-100 bg-light-info overflow-hidden shadow-none">
                <div class="card-body position-relative">
                  <div class="row">
                    <div class="col-sm-7">
                      <div class="d-flex align-items-center mb-7">
                        <div class="rounded-circle overflow-hidden me-6">
                          <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" alt="" width="40" height="40">
                        </div>
                        <h5 class="fw-semibold mb-0 fs-5">Welcome back {{$name}}!</h5>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="border-end pe-4 border-muted border-opacity-10">
                          <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">₹{{round($wallet,2)}}</h3>
                          <p class="mb-0 text-dark">Wallet Balance</p>
                        </div>
                        <div class="ps-4">
                          <h3 class="mb-1 fw-semibold fs-8 d-flex align-content-center">₹{{$income}}<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i></h3>
                          <p class="mb-0 text-dark">Today Profit</p>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div class="welcome-bg-img mb-n7 text-end">
                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/welcome-bg.svg" alt="" class="img-fluid">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div id="content">
            <div class="card-body my-1 pb-0">
                <div class="row">
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="/aeps"><img src="{{url('assets')}}/dist/images/new-folder/aeps.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">AEPS</h6>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="/money-transfer"><img src="{{url('assets')}}/dist/images/new-folder/money-transfer.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">Money Transfer</h6>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="/quick-transfer"><img src="{{url('assets')}}/dist/images/new-folder/qt-blue.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">Quick Transfer</h6>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="{{route('bbps.index')}}"><img src="{{url('assets')}}/dist/images/new-folder/bbps-blue.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">BBPS</h6>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="/payout"><img src="{{url('assets')}}/dist/images/new-folder/payout-blue.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">Payout</h6>
                        </div>
                     </div>
                  </div>
                  
                  <div class="col-6 col-lg-2 get-category" data-key="">
                     <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                        <div class="position-relative bbps-icons">
                               <a href="/recharge-mobile"><img src="{{url('assets')}}/dist/images/new-folder/mobile-postpaid.png" class="card-img-top rounded-0" alt=""></a>
                        </div>
                        <div class=" pt-1 p-1 text-center">
                           <h6 class="fw-semibold fs-4 font-weight-bold">Recharge</h6>
                        </div>
                     </div>
                  </div>
               </div>
               <div  id="extraServices">
                   <div class="row">
                      <div class="col-6 col-lg-2 get-category" data-key="">
                         <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                            <div class="position-relative bbps-icons">
                                   <a href="/aeps"><img src="{{url('assets')}}/dist/images/new-folder/aeps.png" class="card-img-top rounded-0" alt=""></a>
                            </div>
                            <div class=" pt-1 p-1 text-center">
                               <h6 class="fw-semibold fs-4 font-weight-bold">UTI Service</h6>
                            </div>
                         </div>
                      </div>
                      
                      <div class="col-6 col-lg-2 get-category" data-key="">
                         <div class="card hover-img bg-light-red dash-service overflow-hidden rounded-2 justify-content-center">
                            <div class="position-relative bbps-icons">
                                   <a href="/money-transfer"><img src="{{url('assets')}}/dist/images/new-folder/money-transfer.png" class="card-img-top rounded-0" alt=""></a>
                            </div>
                            <div class=" pt-1 p-1 text-center">
                               <h6 class="fw-semibold fs-4 font-weight-bold">Fastag Service</h6>
                            </div>
                         </div>
                      </div>
                   </div>
               </div>
            </div>
            <div class="btnextraServices">
                <div id="viewextraServices">
                    View More
                    <i  class="fa-solid fa-sort-down"></i>
                </div>
            </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-primary shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-primary d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/wallet-white.png">
                    </div>
                    <h6 class="mb-0 ms-3">AEPS</h6>
                    <div class="ms-auto text-primary d-flex align-items-center">
                      <i class="ti ti-trending-up text-primary fs-6 me-1"></i>
                      <span class="fs-2 fw-bold">+ {{$aepsProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayAeps}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayAeps}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-red shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-danger d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/dmt.png">
                    </div>
                    <h6 class="mb-0 ms-3">DMT</h6>
                    <div class="ms-auto text-danger d-flex align-items-center">
                      <i class="ti ti-trending-up text-danger fs-6 me-1"></i>
                      <span class="fs-2 fw-bold">+ {{$dmtProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$yesterDayDmt}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayDmt}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-primary shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-success d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/qt-white.png">
                    </div>
                    <h6 class="mb-0 ms-3">Q Transfer</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-success fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-success">+ {{$qtProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayQt}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayQt}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-red shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-warning d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/payout-white.png">
                    </div>
                    <h6 class="mb-0 ms-3">Payout</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-warning fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-warning">+ {{$payoutProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayPayout}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayPayout}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-red shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-warning d-flex align-items-center justify-content-center dash-icon">
                     <img src="{{url('assets')}}/dist/images/new-folder/mobile-recharge.png">
                    </div>
                    <h6 class="mb-0 ms-3">Recharge(Mobile)</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-warning fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-warning">+ {{$mobileProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayMobile}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayMobile}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-primary shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-success d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/dth-recharge.png">
                    </div>
                    <h6 class="mb-0 ms-3">Recharge(DTH)</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-success fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-success">+ {{$dthProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayDth}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayDth}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-red shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-warning d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/bbps.png">
                    </div>
                    <h6 class="mb-0 ms-3">BBPS</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-warning fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-warning">+ {{$bbpsProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$todayDayBbps}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayBbps}}</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-xl-3">
              <div class="card bg-light-primary shadow-none">
                <div class="card-body p-4">
                  <div class="d-flex align-items-center">
                    <div class="round rounded bg-warning d-flex align-items-center justify-content-center dash-icon">
                      <img src="{{url('assets')}}/dist/images/new-folder/uti.png">
                    </div>
                    <h6 class="mb-0 ms-3">UTI</h6>
                    <div class="ms-auto text-info d-flex align-items-center">
                      <i class="ti ti-trending-down text-warning fs-6 me-1"></i>
                      <span class="fs-2 fw-bold text-warning">+ {{$utiProfit}}%</span>
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-between mt-4">
                    <h3 class="mb-0 fw-semibold fs-7">₹ {{$yesterDayUti}}</h3>
                    <span class="fw-bold">₹ {{$yesterDayUti}}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!--<div class="row">-->
          <!--    <div class="col-12">-->
          <!--      <div class="card">-->
          <!--        <div class="card-body">-->
          <!--          <h5>Last 6 Months Repot</h5>-->
          <!--          <canvas id="myChart"></canvas>-->
          <!--        </div>-->
          <!--      </div>-->
          <!--    </div>-->
          <!--  </div>-->
         
         
         
              
        </div>

@include('users/footer');  
    