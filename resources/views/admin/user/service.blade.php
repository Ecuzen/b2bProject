<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    
    .container {
      max-width: 500px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    
    h1 {
      text-align: center;
    }
    
    .info {
      margin-top: 20px;
    }
    
    .info p {
      margin: 5px 0;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="info">
      <div class="form-validation">
               <form class="needs-validation manage-service" novalidate >
                  <div class="row ">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">AEPS
                           </label>
                           <div class="col-lg-8">
                                @if($aeps == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="aeps" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="aeps" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">BBPS
                           </label>
                           <div class="col-lg-8">
                                @if($bbps == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="bbps" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="bbps" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Recharge
                           </label>
                           <div class="col-lg-8">
                                @if($recharge == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="recharge" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="recharge" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">UTI
                           </label>
                           <div class="col-lg-8">
                                @if($uti == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="uti" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="uti" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Payout
                           </label>
                           <div class="col-lg-8">
                                @if($payout == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="payout" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="payout" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Q-Transfer
                           </label>
                           <div class="col-lg-8">
                                @if($qtransfer == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="qtransfer" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="qtransfer" >
                                @endif
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">DMT
                           </label>
                           <div class="col-lg-8">
                                @if($dmt == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="dmt" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="dmt" >
                                @endif
                           </div>
                        </div>
                        <input type="hidden" name="uid" value="{{$id}}">
                        <div class="mb-3 row">
                           <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
                           <div class="col-lg-6">
                              <button class="btn btn-outline-primary w-10" type="submit">Save Changes</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
    </div>
  </div>
<script>
    $(".manage-service").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url : '/admin-update-users-services',
            method :'post',
            data:formData,
                processData:false,
                contentType:false,
                cache:false,
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
</body>
</html>
