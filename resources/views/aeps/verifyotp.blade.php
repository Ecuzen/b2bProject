<div class="form-floating mb-3">
    <input type="text" class="form-control border border-success numeric-input" placeholder="Enter OTP" id="otp"/>
    <label><i class="ti ti-123 me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Enter OTP</span></label>
</div>
<div class="d-md-flex align-items-center">
    <div class="mt-3 mt-md-0 ms-auto">
        <button type="button" class="btn btn-success font-medium rounded-pill px-4 aeps-verify-otp" >
            <div class="d-flex align-items-center"><i class="ti ti-send me-2 fs-4"></i>Verify OTP</div>
        </button>
    </div>
</div>
<script>
    $(document).ready(function(){  
        $(".aeps-verify-otp").click(function(){
            var otp = $("#otp").val();
            if(otp == "" || otp.length <6)
            {
                info('Please send valid otp');
                return;
            }
            $("#staticBackdrop").modal("show");
            $.ajax({
                url : '/aeps-verify-otp',
                method : 'post',
                data : {
                    'outlet' : $("#outlet").val(),
                    'otp' : $("#otp").val(),
                    '_token' : '{{csrf_token()}}',
                    'lat' : $("#latitude").val(),
                    'log': $("#longitude").val()
                },
                success:function(data)
                {
                    $("#staticBackdrop").modal("hide");
                    if(data.status == 'SUCCESS')
                    {
                        successReload(data.message);
                    }
                    else
                    {
                        errorReload(data.message);
                    }
                }
            })
        })
    })
</script>