@include('admin.header')
<div class="row">
            <div class="col-lg-6 ">
              <div class="card ">
                   <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation">
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Enter Old Password
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control " id="oldPassword" placeholder="Enter Old Password" required>
												<div class="invalid-feedback">
													Enter Old Password
												</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Create New Password
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control " id="createPassword" placeholder="Create New Password" required>
												<div class="invalid-feedback">
													Create New Password
												</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Confirm New Password
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="password" class="form-control " id="confirmPassword" placeholder="Confirm New Password" required>
												<div class="invalid-feedback">
													Confirm New Password
												</div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
                                            <div class="col-lg-6">
                                                <button class="btn btn-outline-primary w-10 submit-setting">Reset Password</button>
                                            </div>
                                        </div>
                </div>
              </div> 
            </div>
          </div>
<script>
    $(".submit-setting").click(function(){
        var createPassword = $("#createPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        if(createPassword !== confirmPassword)
        {
            error('Create Password and confirm Password not same!');
            return;
        }
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/admin-reset-password',
            method : 'post',
            data : {
                'createPassword' : createPassword,
                'oldPassword' : $("#oldPassword").val(),
                '_token' : '{{csrf_token()}}'
            },
            success:function(data)
            {
                $button.html(originalText);
                $button.prop("disabled", false);
                if(data.status == 'SUCCESS')
                {
                    successReload(data.message);
                }
                else
                {
                    error(data.message);
                }
            }
        })
    })
</script>
@include('admin.footer')