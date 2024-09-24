<div class="card overflow-hidden">
            <div class="card-body p-0">
              <img src="{{url('assets')}}/dist/images/backgrounds/profilebg.jpg" alt="" class="img-fluid">
              <div class="row align-items-center">
                <div class="col-lg-4 order-lg-1 order-2">
                  <div class="d-flex align-items-center justify-content-around m-4">
                    <div class="text-center">
                      <i class="ti ti-currency-rupee fs-6 d-block mb-2"></i>
                      <h4 class="mb-0 fw-semibold lh-1">75,000</h4>
                      <p class="mb-0 fs-4">Limit</p>
                    </div>
                    <div class="text-center">
                      <i class="ti ti-discount-check fs-6 d-block mb-2"></i>
                      <h4 class="mb-0 fw-semibold lh-1">{{$limit}}</h4>
                      <p class="mb-0 fs-4">Remaining Limit</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 mt-n3 order-lg-2 order-1">
                  <div class="mt-n5">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                      <div class="linear-gradient d-flex align-items-center justify-content-center rounded-circle" style="width: 110px; height: 110px;";>
                        <div class="border border-4 border-white d-flex align-items-center justify-content-center rounded-circle overflow-hidden" style="width: 100px; height: 100px;";>
                          <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" alt="" class="w-100 h-100">
                        </div>
                      </div>
                    </div>
                    <div class="text-center">
                      <h5 class="fs-5 mb-0 fw-semibold">{{$data->fname}} {{$data->lname}}</h5>
                      <p class="mb-0 fs-4">Phone :- {{$data->mobile}}</p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 order-last">
                  <ul class="list-unstyled d-flex align-items-center justify-content-center justify-content-lg-start my-3 gap-3">
                    <li><button class="btn btn-primary add-account"><i class="ti ti-user-plus"></i>&nbsp;&nbsp;Add Account</button></li>
                    <li><button class="btn btn-warning dmt-logout"><i class="ti ti-logout"></i>&nbsp;&nbsp;Logout</button></li>
                  </ul>
                </div>
              </div>
              <ul class="nav nav-pills user-profile-tab justify-content-end mt-2 bg-light-info rounded-2" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 active d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">
                    <i class="ti ti-user-circle me-2 fs-6"></i>
                    <span class="d-none d-md-block">Accounts</span> 
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link position-relative rounded-0 d-flex align-items-center justify-content-center bg-transparent fs-3 py-6" id="pills-followers-tab" data-bs-toggle="pill" data-bs-target="#pills-followers" type="button" role="tab" aria-controls="pills-followers" aria-selected="false">
                    <i class="ti ti-heart me-2 fs-6"></i>
                    <span class="d-none d-md-block">Recent Txn's</span> 
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <div id="accountData"></div>

<script>
    $(".add-account").click(function(){
    $("#add-account").modal("show");
})
$(".dmt-logout").click(function(){
    $.ajax({
        url : 'dmt-logout',
        success:function()
        {
            location.reload();
        }
    })
})
</script>