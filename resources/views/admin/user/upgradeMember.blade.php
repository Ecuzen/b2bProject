@include('admin.header')
<div class="row">
   <div  class="col-lg-3"></div>
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Upgrade Member</h4>
         </div>
         <div class="card-body p-4">
            <div class="form-validation">
               <form class="needs-validation" novalidate >
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-3 col-form-label" for="validationCustom02">
                              Select User
                           </label>
                           <div class="col-lg-9">
                              <select class="form-control" id="user">
                                  @foreach($userDetails as $user)
                                    <option value="{{$user->userId}}">{{$user->userName}}</option>
                                  @endforeach
                              </select>
                           </div>
                        </div>
                        <!--Date Picker for custom date-->
                        <div class="mb-3 row" id="dateDiv">
                           <label class="col-lg-3 col-form-label datepicker" for="validationCustom03">Package</label>
                           <div class="col-lg-3">
                              <input type="text" class="form-control" id="packageName"  required readonly="">
                           </div>
                           <label class="col-lg-3 col-form-label datepicker" for="validationCustom03">Role</label>
                           <div class="col-lg-3">
                              <input type="text" class="form-control" id="roleName" required readonly="">
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-3 col-form-label" for="validationCustom03">Upgrade Package
                           </label>
                           <div class="col-lg-3">
                              <select class="form-control" id="upPackage">
                                  
                              </select>
                           </div>
                           <label class="col-lg-3 col-form-label" for="validationCustom03">Upgrade Role
                           </label>
                           <div class="col-lg-3">
                              <select class="form-control" id="upRole">

                              </select>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-3 col-form-label" for="validationCustom03">
                           </label>
                           <div class="col-lg-3">
                              <button type="button" class="btn btn-primary upgradePackage">Upgrade Package</button>
                           </div>
                           <label class="col-lg-3 col-form-label" for="validationCustom03">
                           </label>
                           <div class="col-lg-3">
                              <button type="button" class="btn btn-info upgradeRole">Upgrade Role</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
    var allUser = @json($userDetails);
    var allPackage = @json($allPackage);
    var allRole = @json($allRole);
    $("#user").on('change',function(){
        var userId = $("#user").val();
        $.each(allUser, function (index, allUser) {
            if(userId == allUser.userId)
            {
                $("#packageName").val(allUser.Package);
                $("#roleName").val(allUser.roleName);
                /*Upgrade details*/
                var roleId;
                $.each(allRole,function(index,allRole){
                    if(allUser.roleName == allRole.name)
                    {
                        roleId = allRole.id;
                    }
                })
                $("#upRole").empty();
                $("#upPackage").empty();
                $.each(allRole,function(index,allRole){
                    if(roleId > allRole.id)
                    {
                        var newOption = $("<option>", {
                                    value: allRole.id,
                                    text: allRole.name
                                });
                        $("#upRole").append(newOption);
                    }
                })
                $.each(allPackage,function(index,allPackage){
                    if(roleId == allPackage.role)
                    {
                        var newOption = $("<option>", {
                                    value: allPackage.id,
                                    text: allPackage.name
                                });
                        $("#upPackage").append(newOption);
                    }
                })
            }
        });
    })
    $(".upgradeRole").click(function(){
        var user = $("#user").val();
        var role = $("#upRole").val();
        if(user == "" || user == null || role == "" || role == null)
        {
            info('Please send valid data');
            return;
        }
        var $button = $(this);
        var previous = $button.html();
        $button.html('Waiting!!');
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-submit-upgrade-member/role',
            method : 'post',
            data : {
                "user" : user,
                "data" : role,
                "_token" : "{{csrf_token()}}"
            },
            success:function(data)
            {
                $button.html(previous);
                $button.prop('disabled',false);
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
    $(".upgradePackage").click(function(){
        var user = $("#user").val();
        var pkg = $("#upPackage").val();
        if(user == "" || user == null || pkg == "" || pkg == null)
        {
            info('Please send valid data');
            return;
        }
        var $button = $(this);
        var previous = $button.html();
        $button.html('Waiting!!');
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-submit-upgrade-member/package',
            method : 'post',
            data : {
                "user" : user,
                "data" : pkg,
                "_token" : "{{csrf_token()}}"
            },
            success:function(data)
            {
                $button.html(previous);
                $button.prop('disabled',false);
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