@include('admin.header')
<div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    
                                </div><!--end card-header-->
                                <div class="card-body">  
                                    <div class="row">
                                        <div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            <div class="mb-3 row">
                                                <label for="example-text-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Name</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" placeholder="Enter Name" id="name">
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="example-email-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Email</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="email" placeholder="Enter Email" id="email">
                                                </div>
                                            </div> 
                                            <div class="mb-3 row">
                                                <label for="example-tel-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Phone</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="number" placeholder="Enter Phone Number" id="phone">
                                                </div>
                                            </div>
                                             
                                            <div class="mb-3 row">
                                                <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Role</label>
                                                <div class="col-sm-10">
                                                    <select class="form-select userRole" aria-label="Default select example">
                                                        <option selected>select</option>
                                                        @foreach($role as $role)
                                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                                        @endforeach
                                                      </select>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Package</label>
                                                <div class="col-sm-10">
                                                    <select class="form-select package" aria-label="Default select example">
                                                        
                                                      </select>
                                                </div>
                                            </div>
                                             
                                            <div class="mb-3 mb-lg-0 row">
                                                <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end"></label>
                                                <div class="col-sm-10">
                                                    <button class="btn btn-success add-user" type="button">Add User</button>
                                                </div>
                                            </div>                                 
                                        </div>
                                    </div>                                                                      
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div>

<script>
var packages = @json($package);
    $(".userRole").on('change',function(){
        var role = $(".userRole").val();
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
    })

$(".add-user").click(function(){
    var name = $("#name").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var role = $(".userRole").val();
    var pkg = $(".package").val();
    if(name == "" || email == "" || phone == "" || role == "" || role == 'select' || pkg == "")
    {
        error('All data fields are required!!');
        return;
    }
    var $button = $(this);
    $button.data("previousHtml", $button.html());
    $button.html("Waiting!!!");
    $button.prop("disabled", true);
    $.ajax({
        url : '/add-user',
        method : 'post',
        data : {
            "name" : name,
            "email" : email,
            "phone" : phone,
            "role" : role,
            "pkg" : pkg,
            '_token' : '{{csrf_token()}}'
        },
        success:function(data)
        {
            $button.html($button.data("previousHtml"));
            $button.prop("disabled", false);
            if(data.status == 'SUCCESS')
            {
                successReload(data.message);
            }
            else
            {
                info(data.message);
            }
        }
    })
})
</script>
@include('admin.footer')