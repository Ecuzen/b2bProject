@include('users.header')
<div class="container-fluid">
   <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
         <div class="row align-items-center">
            <div class="col-9">
               <h4 class="fw-semibold mb-8">Add Fund By Payment Gateway</h4>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a class="text-muted" href="index.html">Dashboard</a></li>
                     <li class="breadcrumb-item" aria-current="page">By Payment Gateway</li>
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
   <section>
      <div class="row">
         <div class="col-lg-6">
            <div class="card">
                  <div class="card-body">
                    <h5>Add Fund By VPA/UPI</h5>
                    <p class="card-subtitle mb-3 text-danger">
                      All fields are mandatory
                    </p>
                    <form class="" id="raise-form">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-ecuzen " placeholder="UPI/VPA id" id="upi"/>
                        <label>
                            <i class="ti ti-certificate me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">UPI/VPA id</span>
                        </label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-ecuzen " placeholder="Enter Name" id="name"/>
                        <label>
                            <i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Enter Name</span>
                        </label>
                      </div>
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Amount" id="amount"/>
                        <label>
                            <i class="ti ti-currency-rupee me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Amount</span>
                        </label>
                      </div>
                      
                      <div class="d-md-flex align-items-center">
                        <div class="form-check mr-sm-2">
                        </div>
                        <div class="mt-3 mt-md-0 ms-auto">
                          <button type="button" class="btn btn-primary font-medium rounded-pill px-4 by-upi">
                            <div class="d-flex align-items-center">
                              <i class="ti ti-send me-2 fs-4"></i>
                              Submit
                            </div>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
         </div>
         <div class="col-lg-6">
            <div class="card">
               <div class="card-body">
                  <h5>Add Fund By QR Code</h5>
                  <p class="card-subtitle mb-3">
                     Scan QR code to make safe payments
                  </p>
                  <div class="row">
                      <div class="col-md-4"></div>
                      <div class="col-md-4" id="qrcode"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<input type="hidden" value="{{$string}}" id="qrString">
<script>
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
$(document).ready(function(){
    $(".by-upi").click(function(){
        var $button = $(this);
        var previous = $button.html();
        var amount = $("#amount").val();
        var upi = $("#upi").val();
        var name = $("#name").val();
        if(amount  == "" || upi == "" || name == "")
        {
            info("All input fields are required!!");
            return;
        }
        $button.html('Loading!!');
        $button.prop('disabled',true);
        $.ajax({
            url: "/add-fund-by-pg-initiate",
            method: 'POST',
            data: {
                'amount' : amount,
                'upi' : upi,
                'name' : name,
                '_token' : "{{csrf_token()}}"
            },
            success: function(data) {
                $("#raise-form")[0].reset();
                $button.html(previous);
                $button.prop('disabled',false);
                if (data.status == 'SUCCESS')
                success(data.message);
                else
                error(data.message);
            }
        });
    });
    var textToEncode = $("#qrString").val();
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: textToEncode,
        width: 228,
        height: 228,
    });
})
</script>

@include('users.footer')