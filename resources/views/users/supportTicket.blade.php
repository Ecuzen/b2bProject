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
                  <h4 class="fw-semibold mb-8">Support Ticket</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" >Support</li>
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
                        <h4 class="card-title">Raise Ticket</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation"> 
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom02">Select Service<span
                                                    class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <select class="form-control" id="service">
                                                    @foreach($supportType as $support)
                                                    <option value="{{$support->id}}">{{$support->name}}</option>
                                                    @endforeach
                                                </select>
                                                <input type="text" class="form-control "  placeholder="Enter Service Name"  id="other-service">
                                            </div>
                                        </div>
                                         <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Transaction Id
                                                <span class="text-danger remove-mandate">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <input type="text" class="form-control "  placeholder="Enter Transaction Id"  id="txnid">
												<div class="invalid-feedback">
													Transaction Id
												</div>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <label class="col-lg-4 col-form-label" for="validationCustom03">Message
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="col-lg-8">
                                                <textarea class="form-control" placeholder="Message..."  id="message">
                                                    
                                                </textarea>
                                            </div>
                                        </div>
                                        <div class="mb-3 row">
                                            <div class="col-lg-6">
												
                                            </div>
                                            <div class="col-lg-6">
												<button type="button" class="btn btn-outline-success w-100 raise-ticket">Raise Ticket</button>
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
                        <h4 class="card-title" id="card-title">Last recent tickets</h4>
                    </div>
                <div class="card-body p-4" id="txndata">
                    <div class="table-responsive rounded-2 mb-4 overflow-scroll no-scrollbar">
                <table class="table border text-nowrap customize-table mb-0 align-middle ">
                  <thead class="text-dark fs-4">
                    <tr>
                        <th><h6 class="fs-4 fw-semibold mb-0">Ticket ID</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Service</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">TXNID</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Message</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Admin Message</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Status</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Date</h6></th>
                      <th></th>
                    </tr>
                  </thead>
                    <tbody>
                      @foreach($tickets as $ticket)
                    <tr>
                        <td>
                        <div class="d-flex align-items-center">
                          <div class="ms-3">
                            <h6 class="fs-4 fw-semibold mb-0">{{$ticket->ticketid}}</h6>
                          </div>
                        </div>
                      </td>
                      <td><h6 class="fw-semibold mb-0">{{$ticket->service}}</h6></td>
                      <td><h6 class="fw-semibold mb-0">{{$ticket->txnid}}</h6></td>
                      <td><h6 class="fw-semibold mb-0">{{$ticket->message}}</h6></td>
                      <td><h6 class="fw-semibold mb-0">{{$ticket->adminmsg}}</h6></td>
                      <td>
                            @if($ticket->status == 'PROCESSING')
                                <span class="badge bg-light-success rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-check fs-4"></i>{{$ticket->status}}</span>
                            @elseif($ticket->status == 'PENDING')
                                <span class="badge bg-light-warning rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-triangle fs-4"></i></i>{{$ticket->status}}</span>
                            @else
                                <span class="badge bg-light-danger rounded-3 py-2 text-primary fw-semibold fs-2 d-inline-flex align-items-center gap-1"><i class="ti ti-alert-circle fs-4"></i>{{$ticket->status}}</span>
                            @endif
                      </td>
                      <td>
                        <div class="d-flex align-items-center gap-3">
                          <span class="fw-normal">{{$ticket->date}}</span>
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
<script>
var allType = @json($supportType);
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    $(document).ready(function () {
    $('#message').val('');
    $("#other-service").hide();
    $("#service").on('change',function(){
        var service = $("#service").val();
        if(service == 8)
        {
            $("#service").hide();
            $(".remove-mandate").html('');
            $("#other-service").show();
        }
        else
        {
            $("#service").show();
            $("#other-service").hide();
            $(".remove-mandate").html('*');
        }
    })
    $(".raise-ticket").click(function(){
        var service = $("#service").val();
        var txnid = $("#txnid").val();
        if(service == 8 || service == "" || service == 'undefined')
        {
            service = $("#other-service").val();
            if(txnid == "")
            txnid = 'NA';
        }
        else
        {
            $.each(allType,function(index,allType){
                if(allType.id == service)
                {
                    service = allType.name;
                }
            })
        }
        var message = $("#message").val();
        console.log({'service':service,'message':message,'txnid':txnid});
        if(service == "" || txnid == "" || message == "")
        {
            info('Please share all data!!');
            return;
        }
        $.ajax({
            url :"{{route('submitComplain')}}",
            method : 'post',
            data : {
                'service' : service,
                'txnid' : txnid,
                'message' : message,
                '_token' : "{{csrf_token()}}"
            },
            success:function(data)
            {
                if(data.status == 'SUCCESS')
                successReload(data.message);
                else
                error(data.message);
            }
        })
    })
});
</script>
@include('users.footer')