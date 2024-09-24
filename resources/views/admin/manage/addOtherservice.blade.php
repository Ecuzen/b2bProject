@include('admin.header')
<div class="row">
    <div class="col-lg-3 "></div>
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Add Other Service</h4>
         </div>
         <div class="card-body p-4">
            <div class="form-validation">
               <form class="needs-validation" novalidate >
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Service Name
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control" id="name" placeholder="Service Name" required>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Service URL
                           </label>
                           <div class="col-lg-8">
                              <input type="url" class="form-control " id="url" placeholder="Service URL" required>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="mb-3 row">
               <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
               <div class="col-lg-6">
                  <button class="btn btn-outline-primary w-10 add-service">Add Service</button>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
    // $(".add-service").click(function()
    $(document).on("click",'.add-service', function()
    {
        var $button = $(this);
        var name = $("#name").val();
        var url = $("#url").val();
        if(name == "" || url == "")
        {
            info('Please send all inout values');
            return;
        }
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-add-other-service',
            method : 'post',
            data : {
                'name' : name,
                'url' : url,
                '_token' : '{{csrf_token()}}'
            },
            success:function(data)
            {
                $button.text(originalText);
                $button.prop('disabled',false);
                if(data.status == 'SUCCESS')
                successReload(data.message);
                else
                error(data.message);
            }
        })
    })
</script>
@include('admin.footer')