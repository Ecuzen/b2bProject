<input type="hidden" id="billerId" value="{{$data->id}}">
@if($data->param1 != null)
<div class="form-floating mb-3">
    <input type="text" class="form-control border border-ecuzen" id="param1" placeholder="{{$data->param1}}" />
    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->param1}}</span></label>
</div>
@endif
@if($data->param2 != null)
<div class="form-floating mb-3">
    <input type="text" class="form-control border border-ecuzen" id="param2" placeholder="{{$data->param2}}" />
    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->param2}}</span></label>
</div>
@endif
@if($data->param3 != null)
<div class="form-floating mb-3">
    <input type="text" class="form-control border border-ecuzen" id="param3" placeholder="{{$data->param3}}" />
    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->param3}}</span></label>
</div>
@endif
@if($data->param4 != null)
<div class="form-floating mb-3">
    <input type="text" class="form-control border border-ecuzen" id="param4" placeholder="{{$data->param4}}" />
    <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">{{$data->param4}}</span></label>
</div>
@endif
@if($data->fetch_bill != 1)
<div class="form-floating mb-3">
    <input type="email" class="form-control border border-ecuzen" id="amount" placeholder="Enter Amount" />
    <label><i class="ti ti-123 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Enter Amount</span></label>
</div>
@endif

<div class="d-md-flex align-items-center">
    <div class="mt-3 mt-md-0 mx-auto">
        @if($data->fetch_bill == 1)
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
                                     <button type="button" class="btn btn-light-primary text-danger font-medium waves-effect text-start  printButton print-page"><i class="ti ti-printer fs-5"></i> Print </button>                

                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
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
                url : "{{route('bbps.fetch_bill')}}",
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
                url : "{{route('bbps_new.paybill')}}",
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
    
    $(document).on("click", ".printButton", function () {
            var printContents = document.getElementById('receipt-data').innerHTML;
            var newWindow = window.open('', '', 'height=600,width=800');
    
            newWindow.document.write('<html><head><title>Receipt</title>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
    
            newWindow.document.close();
            newWindow.print();
        });
</script>
