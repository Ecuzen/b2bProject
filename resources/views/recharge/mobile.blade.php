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
                  <h4 class="fw-semibold mb-8">Mobile Recharge</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" >Recharge</li>
                      <li class="breadcrumb-item" aria-current="page">Mobile</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/recharge-mobile.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                   <div class="card-header">
                        <h4 class="card-title">Mobile Recharge</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation"> 
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom01">Phone Number
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
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Select Operator<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="operator">
                                                    <option value="0">select</option>
                                                    @foreach($operators as $operator)
                                                    <option value="{{$operator->code}}">{{$operator->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                         <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Amount
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control numeric-input"  placeholder="Enter amount" required id="amount">
												<div class="invalid-feedback">
													Enter amount
												</div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6">
												<button type="button" class="btn btn-outline-success w-100 fetch-plans">Fetch Plans</button>
                                            </div>
                                            <div class="col-lg-6">
												<button type="button" class="btn btn-outline-primary w-100 recharge">Do Recharge</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                        <th><h6 class="fs-4 fw-semibold mb-0">Number</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Operator</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Status</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Amount</h6></th>
                      <th></th>
                    </tr>
                  </thead>
                    <tbody>
                      @foreach($transactions as $transactions)
                    <tr>
                        <td>
                        <div class="d-flex align-items-center">
                          <div class="ms-3">
                            <h6 class="fs-4 fw-semibold mb-0">{{$transactions->mobile}}</h6>
                          </div>
                        </div>
                      </td>
                      <td><h6 class="fw-semibold mb-0">{{$transactions->operator}}</h6></td>
                      <td>
                            @if($transactions->status == 'SUCCESS')
                                <span class="badge bg-light-success rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-check fs-4"></i>{{$transactions->status}}</span>
                            @elseif($transactions->status == 'PENDING')
                                <span class="badge bg-light-warning rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-triangle fs-4"></i></i>{{$transactions->status}}</span>
                            @else
                                <span class="badge bg-light-danger rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-circle fs-4"></i>{{$transactions->status}}</span>
                            @endif
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-3">
                          <span class="fw-normal">{{$transactions->amount}}</span>
                        </div>
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
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel1"> Payment Processing  </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body">
                <h4>Please wait while your payment is being processed...</h4>
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



<!--Plans Modal-->
<div class="modal fade" id="plansModal" tabindex="-1" aria-labelledby="vertical-center-modal" aria-hidden="true" data-bs-backdrop="static"  data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" >
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="myLargeModalLabel"> View Plans </h4> 
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
            </div>
            <div class="modal-body col-lg-12" id="plans-data">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal">Close</button>
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
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    $(document).ready(function(){
        $(".fetch-plans").hide();
       /* $(".recharge").hide();*/
        
        $('#mobile').keyup(function(event) {
            const mobile = $(this).val();
            if(mobile.length === 10)
            {
                $("#staticBackdrop").modal("show");
                $.ajax({
                    url : '/recharge-fetch-operator',
                    method : 'get',
                    data : {
                        "mobile" : mobile,
                    },
                    success:function(data)
                    {
                        $("#staticBackdrop").modal("hide");
                        if(data.status == 'SUCCESS')
                        {
                            $("#operator").val(data.id).change();
                            $("#plansModal").modal("show");
                            $("#plans-data").html(data.plans);
                        }
                        else
                        {  
                            $("#operator").val("0").change();
                            shortmessageinfo(data.message);
                        }
                    }
                })
            }
    });
    
    $('.fetch-plans').click(function(){
        $('#plansModal').modal('show');
    })
    
    $('.recharge').click(function(){
         var mobile = $('#mobile').val();
         var amount = $('#amount').val();
         var operator = $('#operator').val();
         $("#staticBackdrop").modal("show");
        $.ajax({
            url : '/do-recharge',
            method : 'post',
            data : {
                "mobile" : mobile,
                "operator" : operator,
                "amount" : amount,
                "_token" : "{{ csrf_token() }}"
            },
            success:function(data)
            {
                $("#staticBackdrop").modal("hide");
                if(data.status == 'SUCCESS')
                {
                    $("#pop-tool-modal").modal("show");
                    $("#receipt-data").html(data.view);
                }
                else if(data.status == 'ERROR')
                {
                    error(data.message);
                }
                else
                {
                    info(data.message);
                }
            }
        })
        
    })
    
    })
</script>
@include('users.footer')