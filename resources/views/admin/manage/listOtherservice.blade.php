@include('admin.header')
<div class="row">
    <div class="col-lg-3"></div>
   <div class="col-lg-6">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Service List</h4>
         </div>
         <div class="card-body p-4">
            <table id="datatable-buttons" class="table border table-striped table-bordered display text-nowrap">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Service Name</th>
                     <th>Service URL</th>
                     <th>Action</th>
                  </tr>
               </thead>
               @php
               $i=0
               @endphp
               <tbody>
                  @foreach($otherService as $service)
                  <tr>
                     <td>{{++$i}}</td>
                     <td>{{$service->name}}</td>
                     <td><a href="{{$service->link}}" target="_blank">{{$service->link}}</a></td>
                     <td><button class="btn btn-danger delete-service" data-id="{{$service->id}}" >Delete</button></td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<script>
    // $(".delete-service").click(function()
    $(document).on("click",'.delete-service', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        var serviceId = $button.data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
          }).then(result => {
            if (result.isConfirmed) {
            $.ajax({
                url : '/admin-delete-other-service/'+serviceId,
                success:function(data)
                {
                    $button.text(originalText);
                    $button.prop('disabled',false);
                    if(data.status == 'SUCCESS')
                    {
                        Swal.fire("Deleted!", data.message, "success");
                    }
                    else
                    {
                        Swal.fire("Failed!", data.message, "error");
                    }
                }
                
            })
            }
          });
    })
</script>
@include('admin.footer')