<div class="card w-100 position-relative overflow-hidden">
            <div class="px-4 py-3 border-bottom">
              <h5 class="card-title fw-semibold mb-0 lh-sm">Account Lists</h5>
            </div>
            <div class="card-body p-4">
              <div class="table-responsive rounded-2 mb-4">
                    <div class="overflow-x-auto" style="white-space: nowrap;">
                        <table class="table border text-nowrap customize-table mb-0 align-middle">
                          <thead class="text-dark fs-4">
                            <tr>
                              <th><h6 class="fs-4 fw-semibold mb-0">Accounts</h6></th>
                              <th><h6 class="fs-4 fw-semibold mb-0">Name</h6></th>
                              <th><h6 class="fs-4 fw-semibold mb-0">Bank Name</h6></th>
                              <th><h6 class="fs-4 fw-semibold mb-0">Mode</h6></th>
                              <th><h6 class="fs-4 fw-semibold mb-0">Action</h6></th>
                            </tr>
                          </thead>
                          <tbody class="overflow-x-auto" style="white-space: nowrap;">
                              @foreach($data as $acData)
                            <tr>
                              <td>
                                <div class="d-flex align-items-center">
                                  <div class="ms-3">
                                    <h6 class="fs-4 fw-semibold mb-0">{{$acData->accno}}</h6>
                                    <span class="fw-normal">{{$acData->ifsc}}</span>
                                  </div>
                                  @if($acData->verified == 1)
                                  <i class="ti ti-circle-check-filled"></i>
                                  @else
                                  <i class="ti ti-alert-triangle-filled"></i>
                                  @endif
                                </div>
                              </td>
                              <td><p class="mb-0 fw-normal fs-4">{{$acData->name}}</p></td>
                              <td>
                                <div class="d-flex align-items-center">
                                 {{$acData->bankname}}
                                </div>
                              </td>
                              <td>
                                <!--<span class="badge bg-light-success text-success fw-semibold fs-2">Active</span>-->
                                @if($acData->banktype == 'ALL')
                                <button class="btn btn-success dmt-transfer" data-mode="IMPS" data-ifscCode = "{{$acData->ifsc}}"  data-accno = "{{$acData->accno}}" data-bene_id = "{{$acData->bene_id}}">IMPS</button>
                                <button class="btn btn-warning dmt-transfer" data-mode="NEFT" data-ifscCode = "{{$acData->ifsc}}" data-bene_id = "{{$acData->bene_id}}">NEFT</button>
                                @elseif($acData->banktype == 'NEFT')
                                <button class="btn btn-success disabled">IMPS</button>
                                <button  data-accno = "{{$acData->accno}}" class="btn btn-warning dmt-transfer" data-mode="NEFT" data-ifscCode = "{{$acData->ifsc}}" data-bene_id = "{{$acData->bene_id}}">NEFT</button>
                                @else
                                <button class="btn btn-success dmt-transfer"  data-accno = "{{$acData->accno}}" data-mode="IMPS" data-ifscCode = "{{$acData->ifsc}}" data-bene_id = "{{$acData->bene_id}}">IMPS</button>
                                <button class="btn btn-warning disabled">NEFT</button>
                                @endif
                              </td>
                              <td>
                                @if($acData->verified != 1)
                                    <button class="btn btn-success dmt-account-verify" data-bene_id = "{{$acData->bene_id}}" data-bankid = "{{$acData->bankid}}" data-name = "{{$acData->name}}" data-accno = "{{$acData->accno}}">Verify Account</button>
                                @endif
                                <button class="btn btn-danger dmt-delete-account" data-bene_id = "{{$acData->bene_id}}" >Delete Account</button>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
              </div>
            </div>
          </div>
<script>
var previousView = "";
    $(".dmt-delete-account").click(function(){
    var $button = $(this);
    var originalText = $button.text();
    $button.html("Waiting!!!");
    $button.prop("disabled", true);
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
              url : 'dmt-delete-account/'+$button.data('bene_id'),
              success:function(data)
              {
                  if(data.status == 'SUCCESS')
                  {
                      success(data.message);
                      dmtdata();
                  }
                  else
                  {
                      error(data.message);
                  }
              }
          })
        }
      });
})
$(".dmt-account-verify").click(function(){
    var $button = $(this);
    var previous = $button.html();
    $button.html("Loading!!");
    $button.prop('disabled',true);
    $.ajax({
        url : "{{route('dmtAccountVerify')}}",
        method : 'post',
        data : {
            "accno":$button.data("accno"),
            "bene_id":$button.data("bene_id"),
            "bankid":$button.data("bankid"),
            "benename": $button.data("name"),
            "_token" : "{{csrf_token()}}",
        },
        success:function(data)
        {
            $button.html(previous);
            $button.prop('disabled',false);
            if(data.status == 'SUCCESS')
            successReload(data.message);
            else
            error(data.message);
        }
    })
})
var accno= '';
$(".dmt-transfer").click(function(){
    var $button = $(this);
    var bene_id = $button.data('bene_id');
     accno = $button.data('accno');
    var mode = $button.data('mode');
    var ifscCode = $button.data('ifsccode');
    $("#transfer-mode").val(mode);
    $("#transfer-bene-id").val(bene_id);
    $("#transfer-ifscCode").val(ifscCode);
    $("#dmt-transfer").modal("show");
})

$(".dmt-send-money").click(function(){
    var mode = $("#transfer-mode").val();
    var bene_id = $("#transfer-bene-id").val();
    var amount = $("#transfer-amount").val();
    var ifsc = $("#transfer-ifscCode").val();
    if(mode == "" || bene_id == "" || amount == "" || amount <= 0||ifsc == "")
    {
        info('Please send valid data');
        return;
    }
    $(".modal").modal("hide");
    $("#staticBackdrop").modal("show");
    $.ajax({
        url : '/initiate-dmt-transaction',
        method : 'post',
        data : {
            'mode' : mode,
            'accno' : accno,
            'bene_id' : bene_id,
            'amount' : amount,
            'ifsc' : ifsc,
            '_token' : '{{csrf_token()}}',
        },
        success:function(data)
        {
            $("#staticBackdrop").modal("hide");
            if(data.status == 'SUCCESS')
            {
                previousView = data.view;
                txnsuccessNew(data.message,data.view);
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
})
function transactions(otp)
{
    $(".modal").modal("hide");
    $("#staticBackdrop").modal("show");
    $.ajax({
        url : '/do-dmt-transactions',
        method : 'post',
        data :{
            "otp" : otp,
            "_token" : "{{csrf_token()}}"
        },
        success:function(data)
        {
            $(".modal").modal("hide");
            if(data.status == 'SUCCESS')
            {
                $("#pop-tool-modal").modal("show");
                $("#receipt-data").html(data.view);
            }
            else if(data.status == 'INFO')
            {
                $("#otp-pin-modal").modal("show");
                $(".pin-modal-title").html('Please enter 6-digit OTP');
                $(".pin-otp-modal-body").html(previousView);
            }
            else
            {
                error(data.message);
            }
        }
    })
}
</script>