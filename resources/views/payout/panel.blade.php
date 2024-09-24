@include('users.header')
<style>
    .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
</style>
<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Payout</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">Payout</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/payoutlogo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header">
                        <h4 class="card-title">Payout</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation">
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">CUSTOMER NO
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
												<input type="text" class="form-control numeric-input" id="mobile"  placeholder="Mobile number" required maxlength="10">
												<div class="invalid-feedback">
													Mobile number
												</div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Select Account <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="bid">
                                                    <option value="0">select</option>
                                                    @foreach($accounts as $account)
                                                    @if($account->status == 'APPROVED')
                                                    <option value="{{$account->id}}">{{$account->account.'('.$account->ifsc.'  '.$account->name.')'}}</option>
                                                    @endif
                                                    @endforeach
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Select Mode
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="mode">
                                                    <option>IMPS</option>
                                                    <option>NEFT</option>
                                                </select>
                                            </div>
                                        </div>
                                         <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">AMOUNT
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control numeric-input"  placeholder="Enter amount" required id="amount">
												<div class="invalid-feedback">
													Enter amount
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                  <div class="mb-3 row">
                    <div class="col-lg-4">
                        <button class="btn btn-outline-primary w-100 transfer">Transfer</button>
                    </div>
                    <!--<div class="col-lg-4">
                        <button class="btn btn-blue text-white w-100 verify">Verify Account</button>
                    </div>-->
                    <div class="col-lg-4">
                        <button class="btn btn-blue text-white w-100" data-bs-toggle="modal" data-bs-target="#login-modal">Add Account</button>
                    </div>
                </div>
                </div>
              </div> 
            </div>
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header">
                        <h4 class="card-title" id="card-title">Last recent transactions</h4>
                    </div>
                <div class="card-body p-4" id="txndata">
                    <div class="table-responsive rounded-2 mb-4 overflow-scroll no-scrollbar">
                        <table class="table border text-nowrap customize-table mb-0 align-middle ">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Details</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Customer</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Status</h6></th>
                                    <th><h6 class="fs-4 fw-semibold mb-0">Actions</h6></th>
                                  <th></th>
                                </tr>
                            </thead>
                            <tbody id="refresh-account">
                             @foreach($accounts as $account)
                             <tr>
                                 <td>
                                     <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <h6 class="fs-4 fw-semibold mb-0">{{$account->account}}</h6>
                                            <span class="fw-normal">{{$account->ifsc}}</span>
                                        </div>
                                    </div>
                                 </td>
                                 <td><h6 class="fw-semibold mb-0">{{$account->name}}</h6></td>
                                 <td>
                                    @if($account->status == 'PENDING')
                                        <span class="badge bg-light-warning rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-triangle fs-4"></i></i>{{$account->status}}</span>
                                    @else
                                         <span class="badge bg-light-success rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-check fs-4"></i>{{$account->status}}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-rounded btn-danger delete" data-delete= "{{$account->id}}"><i class="ti ti-trash"></i></button>
                                </td>
                             </tr>
                             @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
                      <!-- Add Account Modal --> 
<div id="login-modal" class="modal fade" tabindex="-1" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="{{route('payoutIndex')}}" class="text-success">
                        <span>
                            <img src="{{$logo}}" class="me-3" width="80" alt="" />
                        </span>
                    </a>
                </div>
                <form id="addaccount" class="ps-3 pr-3">
                    <div class="mb-3">
                        <label for="emailaddress1">Customer Name</label>
                            <input  class="form-control"  type="text" id="add-name"  name="name" required="" placeholder="Customer name"/>
                    </div>
                    <div class="mb-3">
                        <label for="password1">Account Number</label>
                        <input class="form-control numeric-input" type="text" required="" name="account" placeholder="Enter Account Number"/>
                    </div>
                    <div class="mb-3">
                        <label for="emailaddress1">IFSC Code</label>
                            <input  class="form-control"  type="text"  name="ifsc" required="" placeholder="IFSC code"/>
                    </div>
                    <div class="mb-3">
                        <label for="password1">Passbook Picture</label>
                        <input class="form-control" type="file" required="" name="passbook" placeholder="Enter your password"/>
                    </div>
                    <div class="mb-3 text-center">
                        <div class="row">
                            <div class="col-lg-6">
                        <button class="btn btn-outline-secondary w-100 verify">Verify Account</button>
                    </div>
                    <div class="col-lg-6">
                        <button class="btn btn-outline-success w-100 addaccount" type="submit">Add Account</button>
                    </div>
                        </div>
                    </div>
                </form>
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
                    <p>
                        <img src="{{url('assets')}}/images/loaders/loader1.gif" alt="Loader..">
                    </p>
                </center>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>

<!--Account delete modal-->
<div id="delete-modal" class="modal fade" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-2 mb-4">
                    <a href="/" class="text-success">
                        <span>
                            <img src="{{url('assets')}}/images/bg-remove-logo.png" class="me-3" width="80" alt="" />
                        </span>
                    </a>
                </div>
                <form class="ps-3 pr-3">
                    <div class="mb-3">
                        <label for="emailaddress1">Type <b>Yes</b> to verify delete account!!</label>
                            <input  class="form-control"  type="text"   id="deleteverify" required="" placeholder="Yes/no"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start conf-delete"> Delete </button>
                    </div>
                    <div class="col-lg-6">
                        <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal"> Close </button>
                    </div>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="receipt-data">

            </div>
            <div class="modal-footer d-flex justify-content-end">
                     <button type="button" class="btn btn-light-primary text-danger font-medium waves-effect text-start  printButton print-page"><i class="ti ti-printer fs-5"></i> Print </button>                
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>

<script>
    var previousView = "";
    $(".transfer").click(function()
    {
        var mobile = $("#mobile").val();
        var bid = $("#bid").val();
        var amount = $("#amount").val();
        if(mobile == "" || mobile.length != 10 || bid == "0" || amount == "" || amount == 0)
        {
            error('Please check and send proper data');
            return;
        }
        $("#staticBackdrop").modal("show");
        $.ajax({
            url : '/initiate-payout',
            method : 'post',
            data : {
                "mobile" : mobile,
                "bid" : bid,
                "amount" : amount,
                "mode" : $("#mode").val(),
                "_token" : "{{ csrf_token()}}"
            },
            success:function(data)
            {
                $("#staticBackdrop").modal("hide");
                if(data.status == 'SUCCESS')
                {
                    previousView = data.view;
                    txnsuccessNew(data.message,data.view);
                }
                else if(data.status == 'INFO')
                {
                    info(data.message);
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
    
     function transactions(otp)
    {
        $(".modal").modal("hide");
        $("#staticBackdrop").modal("show");
        $.ajax({
            url : '/payout-transaction',
            method : 'post',
            data :{
                "otp" : otp,
                "_token" : "{{csrf_token()}}"
            },
            success:function(data)
            {
                if(data.status == 'SUCCESS')
                {
                    $(".modal").modal("hide");
                    $("#pop-tool-modal").modal("show");
                    $("#receipt-data").html(data.view);
                }
                else if(data.status == 'INFO')
                {
                    $(".modal").modal("hide");
                    $("#otp-pin-modal").modal("show");
                    $(".pin-modal-title").html('Please enter 6-digit OTP');
                    $(".pin-otp-modal-body").html(previousView);
                }
                else
                {
                    $(".modal").modal("hide");
                    error(data.message);
                }
            }
        })
    }
    $(function () {
    $('#addaccount').on('submit', function (e) {
      e.preventDefault();
      $("#staticBackdrop").modal("show");
      $("#login-modal").modal('hide');
      var formData = new FormData(this);
      formData.append('_token', '{{ csrf_token() }}');
      $.ajax({
    	type: 'post',
    	url: '/payout-add-account',
    	data:   formData,
                 processData:false,
                 contentType:false,
                 cache:false,
    	success: function (data) 
    	{
    	    $("#staticBackdrop").modal("hide");
    	    if(data.status == 'SUCCESS')
    	    {
    	        $("#refresh-account").html(data.view);
    	        success(data.message);
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
var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
    const inputField = document.getElementById('add-name');
inputField.addEventListener('input', function() {
  let inputValue = inputField.value;
  let filteredValue = inputValue.replace(/[^A-Za-z\s]/g, '');

  if (filteredValue !== inputValue) {
    inputField.value = filteredValue;
  }
});
var deleteid;
$('.delete').click(function() {
  deleteid = $(this).data('delete');
  $("#delete-modal").modal("show");
  $("#deleteverify").val("");
});
$('.conf-delete').click(function() {
  var conf = $("#deleteverify").val();
  if(conf != 'Yes')
  {
      $("#delete-modal").modal("hide");
      return;
  }
  $.ajax({
      url : '/payout-delete-account',
      method : 'post',
      data : {
          "id" : deleteid,
          "_token" : "{{ csrf_token() }}"
      },
      success:function(data)
      {
          $("#delete-modal").modal("hide");
          if(data.status == 'SUCCESS')
          successReload(data.message);
          else
          error(data.message);
      }
  })
});

   $(document).on("click", ".printButton", function () {
            var printContents = document.getElementById('receipt-data').innerHTML;
            var newWindow = window.open('', '', 'height=600,width=800');
    
            newWindow.document.write('<html><head><title>Receipt</title>');
            newWindow.document.write('</head><body>');
            newWindow.document.write(printContents);
            newWindow.document.write('</body></html>');
    
            newWindow.document.close();
            newWindow.print();
        });

</script>
@include('users.footer')