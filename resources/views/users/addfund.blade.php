@include('users/header')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
          <section>
            <div class="row">
                <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5>Fund Request Banks</h5>
                    <p class="card-subtitle mb-3">
                      Select a bank from below list to add Request
                    </p>
                    
                    <div class="row">
                        @foreach($bank as $bank)
                        <div class="col-lg-12 text-dark" id="fundbank">
                            <div style="box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px, rgb(51, 51, 51) 0px 0px 0px 3px; padding : 10px;" class="card">
                               <div class="row g-4">
                                   <div class="col-lg-2">Account:-</div>
                                   <div class="col-lg-4" data-account='{{$bank->account}}'>{{$bank->account}}</div>
                                   <div class="col-lg-2">IFSC:-</div>
                                   <div class="col-lg-4" data-ifsc="{{$bank->ifsc}}">{{$bank->ifsc}}</div>
                               </div>
                               <div class="row g-4">
                                   <div class="col-lg-2">BANK:-</div>
                                   <div class="col-lg-4" data-bank="{{$bank->bank}}">{{$bank->bank}}</div>
                                   <div class="col-lg-2">NAME:-</div>
                                   <div class="col-lg-4" data-name="{{$bank->bname}}">{{$bank->bname}}</div>
                               </div>
                               <div class="row g-4 mt-2">
                                   <div class="col-lg-4"></div>
                                   <div class="col-lg-4"><button class="btn btn-primary font-medium rounded-pill px-4" data-id="{{$bank->id}}"><i class="ti ti-select me-2 fs-4"></i>Select</button></div>
                               </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-lg-6" id="fundsec">
                <!-- ---------------------
                                                    start Primary Border with Icons
                                                ---------------- -->
                <div class="card">
                  <div class="card-body">
                    <h5>Confirm Details for adding request</h5>
                    <p class="card-subtitle mb-3 text-danger">
                      All fields are mandatory
                    </p>
                    <form class="" id="requestform">
                      <div class="form-floating mb-3">
                        <input type="hidden" name="bid" id="bid">
                        <input type="number" class="form-control border border-ecuzen" placeholder="Account Number" readonly="" id="account" />
                        <label>
                            <i class="ti ti-building-bank me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Account Number</span>
                        </label>
                      </div>
                      
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-ecuzen" placeholder="IFSC" readonly="" id="ifsc"/>
                        <label>
                            <i class="ti ti-cash-banknote me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">IFSC</span>
                        </label>
                      </div>
                      
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control border border-ecuzen" placeholder="Name" readonly="" id="name"/>
                        <label>
                            <i class="ti ti-user-dollar me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Name</span>
                        </label>
                      </div>
                      
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control border border-ecuzen" name="amount" placeholder="Amount" />
                        <label>
                            <i class="ti ti-currency-rupee me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Amount</span>
                        </label>
                      </div>
                      
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control border border-ecuzen" placeholder="RRN/UTR" name="utr" />
                        <label>
                            <i class="ti ti-cash me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">RRN/UTR</span>
                        </label>
                      </div>
                      
                      <div class=" mb-3">
                        <input type="date" class="form-control border border-ecuzen" name="date" placeholder="dd-mm-yyyy" max="{{$date}}" />
                      </div>
                      
                      <div class=" mb-3">
                        <input type="file" class="form-control border border-ecuzen" id="proof" name="proof" />
                      </div>
                      
                      <div class="d-md-flex align-items-center">
                        <div class="form-check mr-sm-2">
                        </div>
                        <div class="mt-3 mt-md-0 ms-auto">
                          <button type="submit" class="btn btn-primary font-medium rounded-pill px-4">
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
                <!-- ---------------------
                                                    end Primary Border with Icons
                                                ---------------- -->
              </div>
            </div>
          </section>
        </div>
        
        <script>
            function selectbank(id)
            {
                alert(id);
            }
            $(document).ready(function() {
              $("#fundsec").hide();
            });
        $("#fundbank button").click(function() {
          var account = $(this).closest(".card").find("[data-account]").data("account");
          var ifsc = $(this).closest(".card").find("[data-ifsc]").data("ifsc");
          var name = $(this).closest(".card").find("[data-name]").data("name");
          var id = $(this).closest(".card").find("[data-id]").data("id");
          $("#fundsec").show();
          $("#account").val(account);
          $("#ifsc").val(ifsc);
          $("#name").val(name);
          $("#bid").val(id);
        });
        
        
$(function () {
    $('#requestform').on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData(this);
      formData.append('_token', '{{ csrf_token() }}');
      $.ajax({
    	type: 'post',
    	url: '/addfund-submit',
    	data:   formData,
                 processData:false,
                 contentType:false,
                 cache:false,
                 async:false,
    	success: function (data) 
    	{
    	    if(data.status == 'SUCCESS')
    	    {
    	        success(data.msg);
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
      });
    });
});

    
        </script>
@include('users/footer')