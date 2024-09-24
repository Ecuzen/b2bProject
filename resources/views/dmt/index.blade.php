@include('users/header')
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
                  <h4 class="fw-semibold mb-8">Domestic Money Transfer</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">DMT</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/dmtlogo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="content">
            <div class="row">
                  <div class="col-lg-3"></div>
                  <div class="col-lg-6">
                        <!-- ---------------------
                                                            start Success Border with Icons
                                                        ---------------- -->
                        <div class="card">
                          <div class="card-body">
                            <h5>Money Transfer Login</h5>
                            <!--<p class="card-subtitle mb-3">-->
                            <!--  made with bootstrap elements-->
                            <!--</p>-->
                            <form class="">
                              <div class="form-floating mb-3">
                                <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Enter your phone number" id="phone" maxlength="10"/>
                                <label ><i class="ti ti-device-mobile-check me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3" ></span >Enter your phone number</label >
                              </div>
        
                              <div class="d-md-flex align-items-center">
                                <div class="mt-3 mt-md-0 mx-auto">
                                  <button type="button" class="btn btn-blue font-medium rounded-pill px-4 dmt-login" >
                                    <div class="d-flex align-items-center text-white">
                                      <i class="ti ti-send me-2 fs-4"></i>
                                      Login
                                    </div>
                                  </button>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
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

<!--add account modal-->
<div class="modal fade" id="add-account" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Add Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="add-contact-box">
                      <div class="add-contact-content">
                        <form id="addContactModalTitle">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3 contact-name">
                                <input type="text" id="a-name" class="form-control" placeholder="Name" />
                                <span class="validation-text text-danger"></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3 contact-email">
                                <input type="text" id="a-account" class="form-control numeric-input" placeholder="Account Number" />
                                <span class="validation-text text-danger"></span>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3 contact-phone">
                                <input type="text" id="a-ifsc" class="form-control" placeholder="IFSC Code"/>
                                <span class="validation-text text-danger"></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3 contact-occupation">
                                <!--<input type="text"  placeholder="Occupation" />-->
                                <select id="a-bank" class="form-control">
                                    <option value="0">select</option>
                                    @foreach($bankList as $bank)
                                    <option value="{{$bank->bid}}">{{$bank->bname}}</option>
                                    @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="mb-3 contact-name">
                                <input type="date" id="a-dob" class="form-control" placeholder="Date of birth" />
                                <span class="validation-text text-danger"></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="mb-3 contact-email">
                                <input type="text" id="a-pincode" class="form-control numeric-input" placeholder="Enter PIN Code" />
                                <span class="validation-text text-danger"></span>
                              </div>
                            </div>
                          </div>
                          <!--<div class="row">
                            <div class="col-md-12">
                              <div class="mb-3 contact-location">
                                <input type="text" id="c-location" class="form-control" placeholder="Location" />
                              </div>
                            </div>
                          </div>-->
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button id="btn-add" class="btn btn-success rounded-pill px-4 dmt-add-account">Add</button>
                    <!--<button id="btn-edit" class="btn btn-success rounded-pill px-4">Save</button>-->
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal"> Discard </button>
                  </div>
                </div>
              </div>
            </div>
<!--Payment Modal-->
<div class="modal fade" id="dmt-transfer" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Transfer Money</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="transfer-modal">
                <div class="modal-body">
                    <div class="add-contact-box">
                        <div class="add-contact-content">
                            <form id="addContactModalTitle">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3 contact-email">
                                        <input type="text" id="transfer-amount" class="form-control numeric-input" placeholder="Enter Amount" />
                                        <span class="validation-text text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="transfer-mode">
                                <input type="hidden" id="transfer-bene-id">
                                <input type="hidden" id="transfer-ifscCode">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn-add" class="btn btn-success rounded-pill px-4 dmt-send-money">Send</button>
                    <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal"> Discard </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- After Payment receipt Modal -->
<div class="modal fade" id="pop-tool-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Receipt
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body" id="receipt-data">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>
@if(session()->has('phone'))
    <script>
        // $("#staticBackdrop").modal("show");
        $.ajax({
            url : '/dmt-login',
            method : 'post',
            data :{
                'phone' : "{{session('phone')}}",
                '_token' : '{{csrf_token()}}',
            },
            success:function(data)
            {
                $("#staticBackdrop").modal("hide");
                if(data.status == 'SUCCESS')
                {
                    if(data.activity == 'dashboard')
                    {
                        shortmessagesuccess(data.message);
                        $("#content").html(data.view);
                        dmtdata();
                    }
                    else
                    {
                        shortmessageinfo(data.message);
                        $("#content").html(data.view);
                    }
                }
                else
                {
                    error(data.message);
                }
            }
        })
    </script>
@endif





<script>
    $(".dmt-login").click(function(){
        var phone = $("#phone").val();
        if(phone.length != 10)
        {
            warning('Please enter your 10 digit mobile number');
            return;
        }
        $("#staticBackdrop").modal("show");
        $.ajax({
            url : '/dmt-login',
            method : 'post',
            data :{
                'phone' : phone,
                '_token' : '{{csrf_token()}}',
            },
            success:function(data)
            {
                $("#staticBackdrop").modal("hide");
                if(data.status == 'SUCCESS')
                {
                    if(data.activity == 'dashboard')
                    {
                        shortmessagesuccess(data.message);
                        $("#content").html(data.view);
                        dmtdata();
                    }
                    else
                    {
                        shortmessageinfo(data.message);
                        $("#content").html(data.view);
                    }
                }
                else
                {
                    error(data.message);
                }
            }
        })
    });
    
    $(document).delegate('.dmt-register-submit','click',function(){
        $("#staticBackdrop").modal("show");
    $.ajax({
        url : '/dmt-register/send-data',
        method : 'post',
        data : {
            'fname' : $("#fname").val(),
            'lname' : $("#lname").val(),
            'otp' : $("#otp").val(),
            'pincode' : $("#pincode").val(),
            'address' : $("#address").val(),
            'dob' : $("#dob").val(),
            'mobile' : $("#mobile").val(),
            'key' : $("#key").val(),
            '_token' : '{{csrf_token()}}',
        },
        success:function(data)
        {
            $("#staticBackdrop").modal("hide");
            if(data.status == 'SUCCESS')
            {
                successReload(data.message);
            }
            else
            {
               var errors="";
        	        $.each(data.data, function(key, value) {
        	            errors = errors+"\n"+value[0];
                    });
                    error(errors);
            }
        }
    })
})
function dmtdata()
{
    $("#staticBackdrop").modal("show");
    $.ajax({
       url : '/dmt-call-account-data',
       method : 'GET',
       data : {
           
       },
       success:function(data)
       {
           $("#staticBackdrop").modal("hide");
           if(data.status == 'SUCCESS')
           {
               shortmessagesuccess(data.message);
               $("#accountData").html(data.view);
               
           }
           else
           {
               shortmessageinfo(data.message);
           }
       }
    });
}

$(".dmt-add-account").click(function(){
    var name = $("#a-name").val();
    var account = $("#a-account").val();
    var ifsc = $("#a-ifsc").val();
    var bank = $("#a-bank").val();
    var dob = $("#a-dob").val();
    var pincode = $("#a-pincode").val();
    if(name == "" || account == "" || ifsc == "" || bank == 0|| dob == "" || pincode == "" || pincode.length != 6)
    {
        info('Please send proper data!!');
        return;
    }
    $("#staticBackdrop").modal("show");
    $("#add-account").modal("hide");
    $.ajax({
       url : '/dmt-addaccount',
       method : 'POST',
       data : {
           'name' : name,
           'account' : account,
           'ifsc' : ifsc,
           'bid' : bank,
           'dob' : dob,
           'pincode' : pincode,
           '_token' : "{{csrf_token()}}"
       },
       success:function(data)
       {
           $("#staticBackdrop").modal("hide");
           if(data.status == 'SUCCESS')
           {
               success(data.message);
               dmtdata();
               
           }
           else if(data.status == 'ERROR')
           {
               var errors="";
        	        $.each(data.data, function(key, value) {
        	            errors = errors+"\n"+value[0];
                    });
                    error(errors);
           }
           else
           {
               shortmessageinfo(data.message);
           }
       }
    });
})


var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
</script>
@include('users/footer')