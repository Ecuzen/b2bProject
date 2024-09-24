<div class="form-floating mb-3">
    <input type="text" class="form-control border border-ecuzen" id="base-param" placeholder="{{$data->displayname}}"/>
    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->displayname}}</span></label>
</div>
<input type="hidden" id="billerId" value="{{$data->id}}">
@if($data->ad1_d_name != null)
<div class="form-floating mb-3">
    <input type="email" class="form-control border border-ecuzen" id="param1" placeholder="{{$data->ad1_d_name}}" />
    <label><i class="ti ti-mail me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->ad1_d_name}}</span></label>
</div>
@endif
@if($data->ad2_d_name != null)
<div class="form-floating mb-3">
    <input type="email" class="form-control border border-ecuzen" id="param2"  placeholder="{{$data->ad2_d_name}}" />
    <label><i class="ti ti-mail me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->ad2_d_name}}</span></label>
</div>
@endif
@if($data->ad3_d_name != null)
<div class="form-floating mb-3">
    <input type="email" class="form-control border border-ecuzen" id="param3"  placeholder="{{$data->ad3_d_name}}" />
    <label><i class="ti ti-mail me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->ad3_d_name}}</span></label>
</div>
@endif
@if($data->viewbill != 1)
<div class="form-floating mb-3">
    <input type="email" class="form-control border border-ecuzen" id="amount" placeholder="Enter Amount" />
    <label><i class="ti ti-123 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Enter Amount</span></label>
</div>
@endif

<div class="d-md-flex align-items-center">
    <div class="mt-3 mt-md-0 mx-auto">
        @if($data->viewbill == 1)
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

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel1">Payment Processing</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modal-body-head">Please wait while your payment is being processed...</h4>
                <center>
                    <p>
                        <img src="{{url('assets')}}/images/loaders/loader1.gif" alt="Loader..">
                    </p>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>






<!--Fetch Bill Modal-->
<div class="modal fade" id="pop-tool-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false" >
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
                <button type="button" class="btn btn-blue text-white text-eczn-blue font-medium waves-effect text-start pay-bill" > Pay Bill </button>
            </div>
        </div>
    </div>
</div>

<!--Pay Bill Modal-->
<div class="modal fade" id="pay-bill-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false" >
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

        $(".fetch-bill").click(function() {
            $("#myLargeModalLabel1").html("Fetching your bill due, please wait..");
            $("#modal-body-head").html("Please wait while your request is being completed...");
            var baseParam = $("#base-param").val();
            var param1 = $("#param1").val();
            var param2 = $("#param2").val();
            var param3 = $("#param3").val();

            if (typeof param1 === 'undefined') {
                param1 = "";
            }
            if (typeof param2 === 'undefined') {
                param2 = "";
            }
            
            if (typeof param3 === 'undefined') {
                param3 = "";
            }
            
            $("#staticBackdrop").modal("show");

            $.ajax({
                url: '/bbps-fetch-bill',
                method: 'post',
                data: {
                    'billerId' : $("#billerId").val(),
                    'baseparam': baseParam,
                    'param1': param1,
                    'param2': param2,
                    'param3': param3,
                    '_token': "{{ csrf_token() }}",
                },
                success: function(data) {
                    $("#staticBackdrop").modal("hide");
                    
                    if (data.status == 'SUCCESS') 
                    {
                        $("#pop-tool-modal").modal("show");
                        $("#fetch-data").html(data.view);
                    }
                    else if(data.status == 'INFO')
                    {
                        info(data.message);
                    }
                    else 
                    {
                        $("#staticBackdrop").modal("hide");
                        var errors="";
            	        $.each(data.data, function(key, value) {
            	            errors = errors+"\n"+value[0];
                        });
                        error(errors);
                        }
                }
            });
        });
        $(".pay-bill").click(function() {
            $("#staticBackdrop").modal("show");
            $.ajax({
                url : '/bbps-pay-bill',
                method : 'post',
                data : {
                    'billerId' : $("#billerId").val(),
                    'baseparam': $("#base-param").val(),
                    'amount' : $("#amount").val(),
                    '_token' : "{{csrf_token()}}",
                },
                success:function(data)
                {
                    $("#pop-tool-modal").modal("hide");
                    $("#staticBackdrop").modal("hide");
                    if(data.status == 'SUCCESS')
                    {
                        $("#pay-bill-modal").modal("show");
                        $("#receipt-data").html(data.view);
                    }
                    else if(data.status == 'INFO')
                    {
                        info(data.message);
                    }
                    else
                    {
                        error(data.message);
                    }
                }
            })
        });
</script>
