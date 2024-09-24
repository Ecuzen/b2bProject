@include('admin.header')
<style>
    .loading {
 --speed-of-animation: 0.9s;
 --gap: 6px;
 --first-color: #4c86f9;
 --second-color: #49a84c;
 --third-color: #f6bb02;
 --fourth-color: #f6bb02;
 --fifth-color: #2196f3;
 display: flex;
 justify-content: center;
 align-items: center;
 width: 100px;
 gap: 6px;
 height: 100px;
}

.loading span {
 width: 4px;
 height: 50px;
 background: var(--first-color);
 animation: scale var(--speed-of-animation) ease-in-out infinite;
}

.loading span:nth-child(2) {
 background: var(--second-color);
 animation-delay: -0.8s;
}

.loading span:nth-child(3) {
 background: var(--third-color);
 animation-delay: -0.7s;
}

.loading span:nth-child(4) {
 background: var(--fourth-color);
 animation-delay: -0.6s;
}

.loading span:nth-child(5) {
 background: var(--fifth-color);
 animation-delay: -0.5s;
}

@keyframes scale {
 0%, 40%, 100% {
  transform: scaleY(0.05);
 }

 20% {
  transform: scaleY(1);
 }
}
</style>
<div class="row">
    <div class="col-lg-3"></div>
    <div class="col-lg-6 ">
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title">Commission and Charges</h4>
            </div>
            <div class="card-body p-4">
                <div class="form-validation">
                    <form class="needs-validation" novalidate >
                        <div class="row">
                            <div class="col-xl-12">          
                                <div class="mb-3 row">
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Select Role<span
                                                    class="text-danger">*</span>
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
                                    <label class="col-lg-4 col-form-label" for="validationCustom02">Select Package<span
                                                    class="text-danger">*</span>
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
                <div class="mb-3 row">
                    <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
                    <div class="col-lg-6">
                        <button class="btn btn-outline-primary w-10 create-package">Fetch Details</button>
                    </div>
                </div>
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
            url : '/admin-fetch-package-commission/'+item+'/'+pkg,
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
    /*$(document).ready(function(){
        fetch($("#selectItem").val(),$("#selectPackage").val());
    })*/
    $(document).ready(function() {
      $("#selectItem, #selectPackage").change(function() {
        var selectedItem = $("#selectItem").val();
        var selectedPackage = $("#selectPackage").val();
        fetch(selectedItem, selectedPackage);
      });
    });

</script>
@include('admin.footer')