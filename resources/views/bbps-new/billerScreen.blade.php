@include('users.header')
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{$catHead}}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{$catHead}}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{url('assets')}}/images/dmtlogo.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content">
        <div class="card-body p-4 pb-0">
            <div class="row">
                <div class="col-lg-6">
                    <!-- ---------------------
                                                            start Success Border with Icons
                                                        ---------------- -->
                    <div class="card">
                        <div class="card-body">
                            <h5>{{$biller->biller_name}}</h5>
                            <div class="biller-section">
                                <a href="javascript:void(0)" class="px-4 py-3 bg-hover-light-black d-flex align-items-center chat-user" id="chat_user_4" data-user-id="4">
                                <span class="position-relative">
                                  <img  alt="user8" width="40" height="40" class="rounded-circle" src="{{$biller->icon_url}}">
                                </span>
                                <div class="ms-6 d-inline-block w-75">
                                  <h6 class="mb-1 fw-semibold biller-title">{{$biller->biller_name}}</h6>
                                </div>
                              </a>
                            </div>
                            <div id="paramContent">
                                <input type="hidden" id="billerId" value="{{$biller->id}}">
                                @if($biller->param1 != null)
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-ecuzen" id="param1" placeholder="{{$biller->param1}}" />
                                    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$biller->param1}}</span></label>
                                </div>
                                @endif
                                @if($biller->param2 != null)
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-ecuzen" id="param2" placeholder="{{$biller->param2}}" />
                                    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$biller->param2}}</span></label>
                                </div>
                                @endif
                                @if($biller->param3 != null)
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-ecuzen" id="param3" placeholder="{{$biller->param3}}" />
                                    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$biller->param3}}</span></label>
                                </div>
                                @endif
                                @if($biller->param4 != null)
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control border border-ecuzen" id="param4" placeholder="{{$biller->param4}}" />
                                    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$biller->param4}}</span></label>
                                </div>
                                @endif
                                @if($biller->fetch_bill != 1)
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control border border-ecuzen" id="amount" placeholder="Enter Amount" />
                                    <label><i class="ti ti-123 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Enter Amount</span></label>
                                </div>
                                @endif
                                
                                <div class="d-md-flex align-items-center">
                                    <div class="mt-3 mt-md-0 mx-auto">
                                        @if($biller->fetch_bill == 1)
                                        <button type="button" class="btn btn-blue text-white font-medium rounded-pill px-4 fetch-bill">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-send me-2 fs-4"></i>Fetch Bill
                                            </div>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-blue text-white font-medium rounded-pill px-4 pay-bill">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-send me-2 fs-4"></i>Pay Bill
                                            </div>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary back-button"><i class="ti ti-arrow-back w-100"></i> Back</button>
</div>
<!--Fetch Bill Modal-->
<div class="modal " id="pop-tool-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Fetch Bill
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body" id="fetch-data">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
                <button type="button" class="btn btn-blue text-white font-medium waves-effect text-start pay-bill" > Pay Bill </button>
            </div>
        </div>
    </div>
</div>

<!--Pay Bill Modal-->
<div class="modal " id="pay-bill-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Receipt
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body" id="receipt-data">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".back-button").click(function(){
            window.history.back();
        })
        $(".fetch-bill").click(function(){
            var param1 = $("#param1").val();
            var param2 = $("#param2").val();
            var param3 = $("#param3").val();
            var param4 = $("#param4").val();
            if (typeof param1 === 'undefined')
                param1 = "";
            if (typeof param2 === 'undefined')
                param2 = "";
            if (typeof param3 === 'undefined')
                param3 = "";
            if (typeof param4 === 'undefined')
                param4 = "";
            disableBody();
            $.ajax({
                url : "{{route('subscription.fetch_bill')}}",
                method : 'post',
                data : {
                    "param1" : param1,
                    "param2" : param2,
                    "param3" : param3,
                    "param4" : param4,
                    "billerId" : $("#billerId").val(),
                    "_token" : "{{csrf_token()}}"
                },
                success:function(data)
                {
                    enableBody();
                    if(data.status == 'SUCCESS')
                    {
                        $("#pop-tool-modal").modal("show");
                        $("#fetch-data").html(data.view);
                    }
                    else
                    info(data.message);
                }
            })
        })
        $(".pay-bill").click(function() {
            var param1 = $("#param1").val();
            var param2 = $("#param2").val();
            var param3 = $("#param3").val();
            var param4 = $("#param4").val();
            var amount = $("#amount").val();
            var skey = $("#skey").val();
            if (typeof param1 === 'undefined')
                param1 = "";
            if (typeof param2 === 'undefined')
                param2 = "";
            if (typeof param3 === 'undefined')
                param3 = "";
            if (typeof param4 === 'undefined')
                param4 = "";
            if(typeof amount  === 'undefined')
                amount = $(".amount").val();
            if(typeof sskey  === 'undefined')
                sskey = $(".sskey").val();
            disableBody();
            $(".modal").hide();
            $.ajax({
                url : "{{route('subscription.paybill')}}",
                method : 'post',
                data : {
                    "param1" : param1,
                    "param2" : param2,
                    "param3" : param3,
                    "param4" : param4,
                    "billerId" : $("#billerId").val(),
                    "skey" : skey,
                    "amount" : amount,
                    "_token" : "{{csrf_token()}}"
                },
                success:function(data)
                {
                    enableBody();
                    if(data.status == 'SUCCESS')
                    {
                        $("#pay-bill-modal").modal("show");
                        $("#receipt-data").html(data.view);
                    }
                    else
                    error(data.message);
                }
            })
        });
    })
</script>
@include('users.footer')
