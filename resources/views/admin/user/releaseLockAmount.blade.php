@include('admin.header')
<div class="row">
   <div  class="col-lg-3"></div>
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">{{$tname}}</h4>
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
                                    <option value="{{$user->id}}">{{$user->name}}  {{$user->phone}}({{$user->username}})</option>
                                  @endforeach
                              </select>
                           </div>
                        </div>
                        <!--Date Picker for custom date-->
                        <div class="mb-3 row" id="dateDiv">
                           <label class="col-lg-3 col-form-label datepicker" for="validationCustom03">Lock Amount</label>
                           <div class="col-lg-3">
                              <input type="text" class="form-control" id="lockAmount"  required readonly="">
                           </div>
                           <label class="col-lg-3 col-form-label datepicker" for="validationCustom03">Release Amount</label>
                           <div class="col-lg-3">
                              <input type="text" class="form-control" id="releaseAmount" required >
                           </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-lg-4"></div>
                           <div class="col-lg-4">
                              <button type="button" class="btn btn-primary releaseAmount">Lock Amount</button>
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
    var lockAmount;
    $("#user").on('change',function(){
        var userId = $("#user").val();
        $.each(allUser, function (index, allUser) {
            if(userId == allUser.id)
            {
                lockAmount = allUser.lamount;
                $("#lockAmount").val(lockAmount);
            }
        });
    })
    $(document).ready(function(){
        var userId = $("#user").val();
        $.each(allUser, function (index, allUser) {
            if(userId == allUser.id)
            {
                lockAmount = allUser.lamount;
                $("#lockAmount").val(lockAmount);
            }
        });
    })
    $(".releaseAmount").click(function(){
        var amount = $("#releaseAmount").val();
        if(lockAmount === 'undefined' || lockAmount == ""|| lockAmount == null)
        {
            info('Please refresh the list');
            return;
        }
        if(amount == "" || amount == 0)
        {
            info('Please send valid amount!!');
            return;
        }
        if(lockAmount < amount)
        {
            info('Insufficient amount in user wallet!!');
            return;
        }
        $.ajax({
            url : '/submit-release-lock-amount',
            method : 'post',
            data : {
                "user" : $("#user").val(),
                "amount" : amount,
                "_token" : "{{csrf_token()}}"
            },
            success:function(data)
            {
                if(data.status == 'SUCCESS')
                successReload(data.message);
                else
                error(data.message);
            }
        })
    })
</script>
@include('admin.footer')