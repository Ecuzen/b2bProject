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
                    <img src="{{url('assets')}}/dist/images/new-folder/bank-support.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
                  <div class="col-lg-3"></div>
                  <div class="col-lg-6">
                        <!-- ---------------------
                                                            start Success Border with Icons
                                                        ---------------- -->
                        <div class="card">
                          <div class="card-body box-shadow-1">
                            <h5>Buy UTI Coupon</h5>
                            <!--<p class="card-subtitle mb-3">-->
                            <!--  made with bootstrap elements-->
                            <!--</p>-->
                            <form class="">
                              <div class="form-floating mb-3">
                                <input type="text" class="form-control border border-ecuzen" placeholder="PSA id" value="{{$psaid}}" readonly="" id="psaid"/>
                                <label
                                  ><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start border-success line-eczn ps-3"
                                    >Your PSA Id</span
                                  ></label
                                >
                              </div>
                              <div class="form-floating mb-3">
                                <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Quantity" value="1" id="qty" />
                                <label ><i class="ti ti-mail me-2 fs-4 text-eczn-blue"></i><span class="border-start border-success line-eczn ps-3" >Quantity</span ></label >
                              </div>
                              <div class="form-floating mb-3">
                                <input type="text" class="form-control border border-ecuzen" placeholder="Amount" value="{{$charge}}" id="amount"
                                />
                                <label><i class="ti ti-lock me-2 fs-4 text-eczn-blue"></i><span class="border-start border-success line-eczn ps-3">Amount</span></label>
                              </div>
        
                              <div class="d-md-flex align-items-center">
                                <div class="mt-3 mt-md-0 mx-auto">
                                  <button type="button" class="btn btn-blue font-medium rounded-pill px-4 buy-uti" >
                                    <div class="d-flex align-items-center text-white">
                                      <i class="ti ti-send me-2 fs-4"></i>
                                      Buy
                                    </div>
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



<script>
var initialamt = $("#amount").val();

$(document).ready(function() {
    $("#qty").on('keyup', function() {
        changeqty();
    });

    $("#qty").on('keydown', function() {
        setTimeout(changeqty, 0);
    });
});

    function changeqty() {
        var qty = $("#qty").val();
        if (qty < 1 || qty == "") {
            warning('Coupon should be one or more');
        }
        var total = initialamt * qty;
        $("#amount").val(total);
    }

$(".buy-uti").click(function(){
    var qty = $("#qty").val();
    if(qty == "")
    {
        warning('Coupon should be one or more');
        return;
    }
    $("#staticBackdrop").modal("show");
    $.ajax({
        url : '/buy-uti',
        method : 'post',
        data :{
            'quantity' : qty,
            '_token' : '{{csrf_token()}}',
        },
        success:function(data)
        {
            $(".modal").modal("hide");
            if(data.status == 'SUCCESS')
            {
                $("#pop-tool-modal").modal("show");
                $("#receipt-data").html(data.view);
            }
            else
            {
                error(data.message);
            }
        }
     })
})
var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
</script>
@include('users.footer')