@include('admin.header')
<div class="row">
            <div class="col-lg-6 ">
              <div class="card ">
                   <div class="card-header">
                        <h4 class="card-title">Create Package</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation">
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Select Role<!--<span
                                                    class="text-danger">(optional)</span>-->
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="selectRole">
                                                    @foreach($roles as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Package Name
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control " id="createPackage" placeholder="Enter package name" required>
												<div class="invalid-feedback">
													Enter package name
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
                                                <button class="btn btn-outline-primary w-10 create-package">Create Package</button>
                                            </div>
                                        </div>
                </div>
              </div> 
            </div>
            <div class="col-lg-6 ">
              <div class="card ">
                   <div class="card-header">
                        <h4 class="card-title">Packages List</h4>
                    </div>
                <div class="card-body p-4">
                    <table id="" class="table border table-striped table-bordered display text-nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Role Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        @php
                        $i=0
                        @endphp
                        <tbody>
                            @foreach($packages as $package)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$package->name}}</td>
                                    <td>{{$package->roleName}}</td>
                                    <td><button class="btn btn-warning update" data-id="{{$package->id}}">Update</button> <button class="btn btn-danger delete" data-id="{{$package->id}}">Delete</button></td>
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
                <h6 class="modal-title m-0" id="backDropmodalTitle">Update Package</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                  <div class="mb-3 row">
                        <label class="col-lg-4 col-form-label" for="validationCustom03">Package new name</label>
                            <div class="col-lg-8">
                                <input type="text" class="form-control numeric-input" id="newPackage" placeholder="Package new name" required>
								<div class="invalid-feedback">
									Package new name
								</div>
                            </div>
                    </div>
            </div>
            <input type="hidden" id="pkg">
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm newPackage">Update</button>
                <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(".update").click(function(){
        $("#backDropmodal").modal('show');
        var $button = $(this);
        $("#pkg").val($button.data('id'));
    })
    $(".newPackage").click(function(){
        var newpackage = $("#newPackage").val();
        var pkgid = $("#pkg").val();
        if(newpackage == "" )
        {
            info('Please send valid name');
            return;
        }
        var $button = $(this);
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-package-action/update/'+pkgid,
            method : 'post',
            data : {
                "name" : newpackage,
                '_token' : '{{csrf_token()}}'
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
    $(".delete").click(function(){
        var $button = $(this);
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-package-action/delete/'+$button.data('id'),
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

$(".create-package").click(function(){
    var pkg = $("#createPackage").val();
    if(pkg == "" )
    {
        info('Please send valid name');
        return;
    }
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    $.ajax({
        url : '/admin-create-package',
        method : 'post',
        data : {
            "role" : $("#selectRole").val(),
            'package' : pkg,
            '_token' : '{{csrf_token()}}'
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
</script>
@include('admin.footer')