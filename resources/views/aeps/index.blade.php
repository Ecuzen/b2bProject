@include('users.header')
<style>
    /* Center the loader */
    .loader-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        background-color: #f1f1f1;
    }

    /* Loader animation */
    .loader {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        perspective: 800px;
    }

    .loader:before,
    .loader:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #3498db;
        border-radius: 50%;
        opacity: 0.6;
        transform-origin: 50% 100%;
        animation: rotate 2s linear infinite;
    }

    .loader:before {
        animation-delay: 0s;
    }

    .loader:after {
        animation-delay: -1s;
    }

    @keyframes rotate {
        0% {
            transform: perspective(800px) rotateX(0deg) rotateY(0deg);
        }
        100% {
            transform: perspective(800px) rotateX(-360deg) rotateY(-360deg);
        }
    }

    /* Optional: Add some text below the loader */
    .loading-text {
        margin-top: 20px;
        text-align: center;
        font-size: 20px;
        color: #555;
    }
</style>
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">Aadhar Enabled Payment System</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">AEPS</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{url('assets')}}/images/aeps-logo.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="">
            <div class="card">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <div class="default-tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#balanceenquiry"><i class="la la-home me-2"></i> BALANCE INQUIRY</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#ministatement"><i class="la la-user me-2"></i> MINI STATEMENT</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#withdrawal"><i class="la la-phone me-2"></i> WITHDRAWAL</a>
                            </li>
                            <li class="nav-item aadhar-pay-tfa">
                                <a class="nav-link" data-bs-toggle="tab" href="#aadharpay"><i class="la la-envelope me-2"></i> ADHAAR PAY</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="balanceenquiry" role="tabpanel">
                                <div class="form-validation pt-4">
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        CUSTOMER NO
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="mobile" placeholder="Mobile number" required maxlength="10" />
                                                        <div class="invalid-feedback">
                                                            Mobile number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom02">BANK <span class="text-danger">*</span> </label>
                                                    <div class="col-lg-8">
                                                        <!--<input type="text" class="form-control" id="validationCustom02"  placeholder="Your valid email.." required>-->
                                                        <select class="form-control" id="bankselect">
                                                            <option>select</option>
                                                            @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please enter a Email.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        AADHAR NUMBER
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="aadhar" placeholder="Aadhar Number" required maxlength="12" />
                                                        <div class="invalid-feedback">
                                                            Aadhar Number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01"> </label>
                                                    <div class="col-lg-8">
                                                        <button class="form-control balance-enquiry btn btn-primary" type="button">Fetch Balance</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="ministatement">
                                <div class="form-validation pt-4">
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        CUSTOMER NO
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="mobile" placeholder="Mobile number" required maxlength="10" />
                                                        <div class="invalid-feedback">
                                                            Mobile number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom02">BANK <span class="text-danger">*</span> </label>
                                                    <div class="col-lg-8">
                                                        <!--<input type="text" class="form-control" id="validationCustom02"  placeholder="Your valid email.." required>-->
                                                        <select class="form-control" id="bankselect">
                                                            <option>select</option>
                                                            @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please enter a Email.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        AADHAR NUMBER
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="aadhar" placeholder="Aadhar Number" required maxlength="12" />
                                                        <div class="invalid-feedback">
                                                            Aadhar Number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01"> </label>
                                                    <div class="col-lg-8">
                                                        <button class="form-control ministatement btn btn-primary" type="button">Get Mini Statement</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="withdrawal">
                                <div class="form-validation pt-4">
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        CUSTOMER NO
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="mobile" placeholder="Mobile number" required maxlength="10" />
                                                        <div class="invalid-feedback">
                                                            Mobile number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom02">BANK <span class="text-danger">*</span> </label>
                                                    <div class="col-lg-8">
                                                        <!--<input type="text" class="form-control" id="validationCustom02"  placeholder="Your valid email.." required>-->
                                                        <select class="form-control" id="bankselect">
                                                            <option>select</option>
                                                            @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please enter a Email.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        AADHAR NUMBER
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="aadhar" placeholder="Aadhar Number" required maxlength="12" />
                                                        <div class="invalid-feedback">
                                                            Aadhar Number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        Amount
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="amount" placeholder="Enter Amount" required />
                                                        <div class="invalid-feedback">
                                                            Enter Amount
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01"> </label>
                                                    <div class="col-lg-8">
                                                        <button class="form-control withdrawal btn btn-primary" type="button">Withdrawal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="aadharpay">
                                <div class="form-validation pt-4">
                                    <form class="needs-validation" novalidate>
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        CUSTOMER NO
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="mobile" placeholder="Mobile number" required maxlength="10" />
                                                        <div class="invalid-feedback">
                                                            Mobile number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom02">BANK <span class="text-danger">*</span> </label>
                                                    <div class="col-lg-8">
                                                        <!--<input type="text" class="form-control" id="validationCustom02"  placeholder="Your valid email.." required>-->
                                                        <select class="form-control" id="bankselect">
                                                            <option>select</option>
                                                            @foreach($banks as $bank)
                                                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback">
                                                            Please enter a Email.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        AADHAR NUMBER
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="aadhar" placeholder="Aadhar Number" required maxlength="12" />
                                                        <div class="invalid-feedback">
                                                            Aadhar Number
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01">
                                                        Amount
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control numeric-input" id="amount" placeholder="Enter Amount" required maxlength="12" />
                                                        <div class="invalid-feedback">
                                                            Enter Amount
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3 row">
                                                    <label class="col-lg-4 col-form-label" for="validationCustom01"> </label>
                                                    <div class="col-lg-8">
                                                  <button class="form-control aadharpay-tfa btn btn-primary" type="button">Two Factor Varification</button>
                                                        <!--<button class="form-control aadharpay btn btn-primary" type="button">Aadhar Pay</button>-->
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
            </div>
        </div>
    </div>
</div>

<div class="modal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel1">Request Processing</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4>Please wait while your request is being processed...</h4>
                <center>
                    <div class="loader"></div>
                </center>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="aeps-receipt-modal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel">
                    Receipt
                </h4>
                <button type="button" class="btn-close btn-closess" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body" id="aeps-receipt-data"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start btn-closess" data-bs-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var aptfa = @json($aptfa);
        var numericInputs = document.getElementsByClassName('numeric-input');
        for (var i = 0; i < numericInputs.length; i++) {
            numericInputs[i].addEventListener('input', function(event) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }

 $(document).on('click', '.btn-closess', function () {
        location.reload();
    });


    $(".balance-enquiry").click(function(){
        var mobile = $("#balanceenquiry #mobile").val();
        var bank = $("#balanceenquiry #bankselect").val();
        var aadhar = $("#balanceenquiry #aadhar").val();
        if(mobile.length != 10 || bank == 'select' || aadhar.length != 12)
        {
            info('Please send proper data');
            return;
        }
        $("#staticBackdrop").modal("show");
        var url = '/aeps-balance-enquiry';
        var data = {
            '_token':'{{csrf_token()}}',
            'mobile' : mobile,
            'bank' : bank,
            'aadhar' : aadhar,
            'lat' : $("#latitude").val(),
            'log': $("#longitude").val()
        };
        captureFinger(url,data);
    })

    $(".ministatement").click(function(){
        var mobile = $("#ministatement #mobile").val();
        var bank = $("#ministatement #bankselect").val();
        var aadhar = $("#ministatement #aadhar").val();
        if(mobile.length != 10 || bank == 'select' || aadhar.length != 12)
        {
            info('Please send proper data');
            return;
        }
        $("#staticBackdrop").modal("show");
        var url = '/aeps-mini-statement';
        var data = {
            '_token':'{{csrf_token()}}',
            'mobile' : mobile,
            'bank' : bank,
            'aadhar' : aadhar,
            'lat' : $("#latitude").val(),
            'log': $("#longitude").val()
        };
        captureFinger(url,data);
    })
    
    
// $(document).on("click",".aadharpay").click(function(){
    $(document).on("click", ".aadharpay", function () {
        var mobile = $("#aadharpay #mobile").val();
        var bank = $("#aadharpay #bankselect").val();
        var aadhar = $("#aadharpay #aadhar").val();
        var amount = $("#aadharpay #amount").val();
        if(mobile.length != 10 || bank == 'select' || aadhar.length != 12|| amount == ""|| amount == 0)
        {
            info('Please send proper data');
            return;
        }
        $("#staticBackdrop").modal("show");
        var url = '/aeps-cash-aadharpay';
        var data = {
            '_token':'{{csrf_token()}}',
            'mobile' : mobile,
            'bank' : bank,
            'aadhar' : aadhar,
            'amount' : amount,
            'lat' : $("#latitude").val(),
            'log': $("#longitude").val()
        };
        captureFinger(url,data);
    })
    
    // $(".aadhar-pay-tfa").click(function(){
    //     if(aptfa == false)
    //     {
    //         location.href = "{{route('loadApTfa')}}";
    //     }
    // })
    
$(document).on("click", ".transaction-tfa", function () {
    $("#staticBackdrop").modal("show");
    var url = '/aeps-verify-tfa-finger';
    var data = {'_token':'{{csrf_token()}}','isFor' : true,'lat' : $("#latitude").val(),'log': $("#longitude").val(),'ttfa': 'ttfa'};
    captureFinger(url, data);
});


$(document).on("click", ".aadharpay-tfa", function () {
    $("#staticBackdrop").modal("show");
    var url = '/aeps-verify-aptfa-finger';
    var data = {'_token':'{{csrf_token()}}','isFor' : true,'lat' : $("#latitude").val(),'log': $("#longitude").val(),'ttfa': 'atfa'};
    captureFinger(url, data);
});


function transactionTFA(data) {
    if (data.status == 'SUCCESS') {
        success(data.message);
        $(".aadharpay-tfa").html('Aadhar Pay');
        $(".aadharpay-tfa").removeClass("aadharpay-tfa").addClass("aadharpay");
    }
}

function transactionTTFA(data) {
    if (data.status == 'SUCCESS') {
        success(data.message);
        $(".transaction-tfa").html('Withdrawal');
        $(".transaction-tfa").removeClass("transaction-tfa").addClass("withdrawal");
    }
}

$(document).on("click", ".withdrawal", function () {
    var mobile = $("#withdrawal #mobile").val();
    var bank = $("#withdrawal #bankselect").val();
    var aadhar = $("#withdrawal #aadhar").val();
    var amount = $("#withdrawal #amount").val();
    
    if (mobile.length != 10 || bank == 'select' || aadhar.length != 12 || amount == "" || amount == 0) {
        info('Please send proper data');
        return;
    }

    $("#staticBackdrop").modal("show");
    var url = '/aeps-cash-withdrawal';
    var data = {
        '_token': '{{csrf_token()}}',
        'mobile': mobile,
        'bank': bank,
        'aadhar': aadhar,
        'amount': amount,
        'lat' : $("#latitude").val(),
        'log': $("#longitude").val()
    };
    captureFinger(url, data);
});

</script>
@include('users.footer')
