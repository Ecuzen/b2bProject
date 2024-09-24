@include('users.header')
<style>
    .loader {
  width: 40px;
  height: 40px;
  position: relative;
  margin: 0 auto;
}

.loader:before,
.loader:after {
  content: "";
  position: absolute;
  top: 0;
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.loader:before {
  left: -45px;
  background-color: #3498db;
  animation: loader-anim-before 1s infinite ease-in-out;
}

.loader:after {
  left: 45px;
  background-color: #2ecc71;
  animation: loader-anim-after 1s infinite ease-in-out;
}

@keyframes loader-anim-before {
  0%,
  100% {
    transform: translateX(0);
  }
  50% {
    transform: translateX(90px);
  }
}

@keyframes loader-anim-after {
  0%,
  100% {
    transform: translateX(0);
  }
  50% {
    transform: translateX(-90px);
  }
}

</style>
<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">PSA</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">PSA</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/psa-logo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="card">
                 <div class="card-body">
                    <h5>PSA Registration</h5>
                    <form class="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success " placeholder="Full Name" value="{{$details['name']}}" id="name"/>
                            <label><i class="ti ti-user me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Full Name</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success numeric-input" placeholder="Mobile Number" value="{{$details['phone']}}" id="phone"/>
                            <label><i class="ti ti-phone me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Mobile Number</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control border border-success" placeholder="Email" value="{{$details['email']}}" id="email"/>
                            <label><i class="ti ti-mail me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Email address</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success numeric-input" placeholder="Aadhar Number" value="{{$details['adhaar']}}" id="aadhar"/>
                            <label><i class="ti ti-certificate me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Aadhar Number</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success" placeholder="PAN Number" value="{{$details['pan']}}" id="pan"/>
                            <label><i class="ti ti-certificate me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">PAN Number</span></label>
                        </div>
                        <div class="mb-3">
                            <select class="form-control border border-success" id="state">
                                <option value="0">select</option>
                                @foreach ($states as $state)
                                <option value="{{$state->code}}">{{$state->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success" placeholder="Shop Location" value="{{$details['shopaddress']}}" id="slocation"/>
                            <label><i class="ti ti-building-store me-2 fs-4 text-success"></i><span class="border-start border-success ps-3">Shop Location</span></label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success" placeholder="Resident Address" value="{{$details['address']}}" id="address"/>
                            <label><i class="ti ti-map-pin me-2 fs-4 text-success"></i><span class="border-start border-success ps-3"></span>Resident Address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-success numeric-input" placeholder="PIN Code" value="{{$details['pincode']}}" id="pincode"/>
                            <label><i class="ti ti-map-pin-code me-2 fs-4 text-success"></i><span class="border-start border-success ps-3"></span>PIN Code</label>
                        </div>
                        <div class="d-md-flex align-items-center">
                        <div class="mt-3 mt-md-0 ms-auto">
                          <button type="button" class="btn btn-success font-medium rounded-pill px-4 submit-psa" >
                            <div class="d-flex align-items-center"><i class="ti ti-send me-2 fs-4"></i>Submit</div>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- ---------------------
                                                    end Success Border with Icons
                                                ---------------- -->
              </div>
          </div>
</div>
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
                    <div class="loader"></div>
                </center>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>

<script>
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    $(".submit-psa").click(function(){
        var fields = {
            "pincode": $("#pincode").val(),
            "address": $("#address").val(),
            "slocation": $("#slocation").val(),
            "pan": $("#pan").val(),
            "aadhar": $("#aadhar").val(),
            "email": $("#email").val(),
            "phone": $("#phone").val(),
            "name": $("#name").val(),
        };
        
        var fieldList = Object.entries(fields)
        .reduce((acc, [name, value]) => {
            acc[name] = value;
            return acc;
        }, {});
    
    var blankFields = Object.keys(fieldList)
        .filter(key => fieldList[key].trim() === '');
    
        if (blankFields.length > 0) {
            error("The following fields are blank: "+blankFields);
            return;
        }
        if($("#state").val() == 0)
        {
            error('Please select your state');
            return;
        }
        $("#staticBackdrop").modal("show");
        fieldList["_token"] = "{{csrf_token()}}";
        fieldList["state"] = $("#state").val();
        $.ajax({
        url: '/submit-psa',
        method: 'POST',
        data: fieldList,
        success: function(response) {
            $("#staticBackdrop").modal("hide");
            if(response.status =='SUCCESS')
            {
                successReload(response.message);
            }
            else if(response.status =='INFO')
            {
                info(response.message);
            }
            else
            {
                var errors="";
        	        $.each(response.data, function(key, value) {
        	            errors = errors+"\n"+value[0];
                    });
                    error(errors);
            }
        }
    });

    })
</script>
@include('users.footer')