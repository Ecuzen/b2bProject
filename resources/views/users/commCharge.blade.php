@include('users.header')
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Add Fund Request</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="index.html">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Fund Request</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
</div>

<div class="row mx-0">
    <div class="col-lg-3"></div>
    <div class="col-lg-6 ">
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title">Commission and Charges</h4>
            </div>
            <div class="card-body p-4">
                <div class="form-validation">
                    <form class="needs-validation" novalidate="">
                        <div class="row">
                            <div class="col-xl-12">          
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Select Role<span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-4">
                                        <select class="form-control" id="selectItem">
                                            @foreach($labeles as $labele)
                                                <option value="{{$labele->id}}">{{$labele->label}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>        
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Select Package<span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-4">
                                        <select class="form-control" id="selectPackage">
                                            @foreach($packages as $package)
                                                <option value="{{$package->id}}">{{$package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>                                     
                            </div>
                        </div>
                    </form>
                </div>
                <!--<div class="mb-3 row">-->
                <!--    <label class="col-lg-3 col-form-label" for="validationCustom02"></label>-->
                <!--    <div class="col-lg-6">-->
                <!--        <button class="btn btn-outline-primary w-10 create-package">Fetch Details</button>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div> 
    </div>
</div>

<section id="transactions">
            
</section>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel1">Request Processing</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                <h4>Please wait while your request is being processed...</h4>
                <center>
                    <div class="loading">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                </center>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>
<script>
    function fetch(item,pkg)
    {
        $.ajax({
            url : '/user-fetch-package-commission/'+item+'/'+pkg,
            success:function(data)
            {
                if(data.status == 'SUCCESS')
                {
                    $("#transactions").html(data.view);
                }
                else
                {
                    $("#transactions").html(data.message);
                }
            }
        })
    }
    $(document).ready(function(){
        fetch($("#selectItem").val(),$("#selectPackage").val());
    })
    $(document).ready(function() {
      $("#selectItem, #selectPackage").change(function() {
        var selectedItem = $("#selectItem").val();
        var selectedPackage = $("#selectPackage").val();
        fetch(selectedItem, selectedPackage);
      });
    });

</script>

@include('users.footer')