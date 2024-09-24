@include('admin.header')
<div class="row">
    <div class="col-lg-3 "></div>
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Manage all services</h4>
         </div>
         <div class="card-body p-4">
            <div class="form-validation">
               <form class="needs-validation manage-service" novalidate >
                  <div class="row ">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">AEPS
                           </label>
                           <div class="col-lg-8">
                                @if($serviceData->aeps == 1)
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
                                @if($serviceData->bbps == 1)
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
                                @if($serviceData->recharge == 1)
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
                                @if($serviceData->uti == 1)
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
                                @if($serviceData->payout == 1)
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
                                @if($serviceData->qtransfer == 1)
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
                                @if($serviceData->dmt == 1)
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="dmt" checked="">
                                @else
                                    <input class="form-check-input form-control" type="checkbox" value="1" name="dmt" >
                                @endif
                           </div>
                        </div>
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
   </div>
</div>
<script>
    $(".manage-service").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url : '/admin-save-changes-services',
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
@include('admin.footer')