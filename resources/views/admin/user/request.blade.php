@include('admin.header')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">  
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($request as $request)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$request->first}} {{$request->last}}</td>
                            <td>{{$request->mobile}}</td>
                            <td>{{$request->email}}</td>
                            <td>{{$request->roleName}}</td>
                            <td><button class="btn btn-success approve-user" data-member="{{$request->id}}" data-role="{{$request->role}}">Approve</button> <button class="btn btn-danger reject-user" data-member = "{{$request->id}}">Reject</button> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
<div class="modal fade" id="backDropmodal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="backDropmodalTitle">Select package</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                <div class="mb-3 row">
                    <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Package</label>
                    <div class="col-sm-10">
                        <select class="form-select package" aria-label="Default select example">
                                                        
                        </select>
                        <input type="hidden" id="requestId" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-success btn-sm text-dark approve">Approve</button>
                <button type="button" class="btn btn-soft-secondary btn-sm text-warning" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var packages = @json($package);
    // $(".approve-user").click(function()
     $(document).on("click",'.approve-user', function()
    {
        var role = $(this).data('role');
        var pkg = $(".package");
        pkg.empty();
        $.each(packages, function (index, packages) {
            var option = $('<option>')
                .val(packages.id)
                .text(packages.name);
                if (packages.role == role) {
                    option.prop('selected', true);
                    pkg.append(option);
                    }
            });
        $("#requestId").val($(this).data('member'));
        $("#backDropmodal").modal("show");
    })
    // $(".approve").click(function()
     $(document).on("click",'.approve', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        $.ajax({
            url : '/approve-user-request',
            method : 'post',
            data : {
                "id" : $("#requestId").val(),
                'package' : $(".package").val(),
                '_token' : "{{csrf_token()}}"
            },
            success:function(data)
            {
                $button.text(originalText);
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
// $(".reject-user").click(function()
 $(document).on("click",'.reject-user', function()
{
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    $.ajax({
        url : '/reject-user/'+$button.data('member'),
        success:function(data)
        {
            $button.text(originalText);
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