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
                    <img src="{{url('assets')}}/images/aeps-logo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
        <div class="col-lg-6 offset-3">
            <div class="card">
                 <div class="card-body">
                    <h5>AEPS KYC</h5>
                    <form class="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Outlet Id" value="{{$aadhar}}" id="aadhar"  readonly/>
                            <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn  ps-3">Aadhar Number</span></label>
                        </div>
                        <div class="d-md-flex align-items-center">
                            <div class="mt-3 mt-md-0 mx-auto">
                                <button type="button" class="btn btn-blue font-medium rounded-pill px-4 aeps-verify-finger" >
                                    <div class="d-flex align-items-center text-white"><i class="ti ti-send me-2 fs-4"></i>Verify Finger data</div>
                                </button>
                            </div>
                        </div>
                    </form>
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
<script>
    $(".aeps-verify-finger").click(function(){
        $("#staticBackdrop").modal("show");
        var url = '/aeps-verify-finger';
        var data = {'_token':'{{csrf_token()}}','lat' : $("#latitude").val(),'log': $("#longitude").val()};
        captureFinger(url,data);
    })
</script>

@include('users.footer')