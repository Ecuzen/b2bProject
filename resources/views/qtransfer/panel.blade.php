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
               <h4 class="fw-semibold mb-8">Qtransfer Transfer</h4>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                     <li class="breadcrumb-item" aria-current="page">Qtransfer</li>
                  </ol>
               </nav>
            </div>
            <div class="col-3">
               <div class="text-center mb-n5">  
                  <img src="{{url('assets')}}/images/qtransferlogo.png" alt="" class="img-fluid mb-n4">
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-6">
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Qtransfer</h4>
            </div>
            <div class="card-body p-4">
               <div class="form-validation">
                  <form class="needs-validation" novalidate >
                     <div class="row">
                        <div class="col-xl-12">
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom01">CUSTOMER MOB.
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
                              <label class="col-lg-4 col-form-label" for="validationCustom02">BANK  <span
                                 class="text-danger">(optional)</span>
                              </label>
                              <div class="col-lg-8">
                                 <!--<input type="text" class="form-control" id="validationCustom02"  placeholder="Your valid email.." required>-->
                                 <select class="form-control" id="bankselect">
                                    <option>select</option>
                                    @foreach($global as $global)
                                    <option value="{{$global->ifscGlobal}}">{{$global->name}}</option>
                                    @endforeach
                                 </select>
                                 <div class="invalid-feedback">
                                    Please enter a Email.
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom03">ACCOUNT NO
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control numeric-input" id="account" placeholder="Enter account number" required>
                                 <div class="invalid-feedback">
                                    Enter account number
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom03">IFSC
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control" id="ifsc" placeholder="Enter IFSC code" required>
                                 <div class="invalid-feedback">
                                    Enter IFSC code
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom03">Name
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control" id="name" placeholder="Customer name" required>
                                 <div class="invalid-feedback">
                                    Customer name
                                 </div>
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
                  <div class="col-lg-4">
                     <button class="btn btn-blue text-white w-100 verify">Verify Account</button>
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
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Details</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Customer</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Status</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Amount</h6>
                           </th>
                           <th></th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($transactions as $transactions)
                        <tr>
                           <td>
                              <div class="d-flex align-items-center">
                                 <div class="ms-3">
                                    <h6 class="fs-4 fw-semibold mb-0">{{$transactions->account}}</h6>
                                    <span class="fw-normal">{{$transactions->ifsc}}</span>
                                 </div>
                              </div>
                           </td>
                           <td>
                              <h6 class="fw-semibold mb-0">{{$transactions->bname}}</h6>
                           </td>
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
<!--Payment Modal -->
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
   $("#bankselect").change(function(){
       $("#ifsc").val($("#bankselect").val());
   })
   var previousView = "";
   $(".transfer").click(function(){
       var mobile = $("#mobile").val();
       var account = $("#account").val();
       var ifsc = $("#ifsc").val();
       var name = $("#name").val();
       var amount = $("#amount").val();
       if(mobile.length != 10 || account.length < 8 || ifsc.length < 10 || name == "" || amount == "" || amount == "0")
       {
           error('Please check all fields and send valid data');
           return;
       }
       $("#staticBackdrop").modal("show");
       $.ajax({
           url : '/qtransfer-initiate',
           method : 'post',
           data : {
               "mobile" : mobile,
               "account" : account,
               "ifsc" : ifsc,
               "name" : name,
               "amount" : amount,
               "_token" : "{{ csrf_token() }}"
           },
           success:function(data)
           {
               $("#staticBackdrop").modal("hide");
               if(data.status == 'SUCCESS')
               {
                   previousView = data.view;
                   txnsuccessNew(data.message,data.view);
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
   
     function transactions(otp)
   {
       $(".modal").modal("hide");
       $("#staticBackdrop").modal("show");
       $.ajax({
           url : 'qtransfer-transaction',
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
   
   
   $(document).ready(function() {
       var txndata = $("#txndata").html();
       var cardtitle = $("#card-title").html();
         $('#mobile').keyup(function() {
             var mobile = $(this).val();
             if(mobile.length === 10)
             {
                 $.ajax({
                     url : '/fetch-data-by-number',
                     method : 'get',
                     data : {
                         "mobile" : mobile,
                     },
                     success:function(data)
                     {
                         if(data.status == 'SUCCESS')
                         {
                             $("#card-title").html(data.head);
                             shortmessagesuccess(data.message);
                             $("#txndata").html(data.view);
                         }
                         else if(data.status == 'INFO')
                         {
                             shortmessageinfo(data.message);
                             $("#txndata").html(txndata);
                             $("#card-title").html(cardtitle);
                         }
                         else
                         {
                             shortmessageerror(data.message);
                             $("#txndata").html(txndata);
                             $("#card-title").html(cardtitle);
                         }
                     }
                 })
             }
         });
        $(".verify").click(function(){
            var mobile = $("#mobile").val();
            var account = $("#account").val();
            var ifsc = $("#ifsc").val();
            if(mobile == "" || mobile.length != 10 || account == "" || ifsc == "")
            {
                info('Please share all data');
                return;
            }
            $.ajax({
                url : "{{route('accountVerify')}}",
                method : 'post',
                data : {
                    "phone" : mobile,
                    "account" : account,
                    "ifsc" : ifsc,
                    "_token" : "{{csrf_token()}}"
                },
                success:function(data)
                {
                    if(data.status == 'SUCCESS')
                    {
                        success(data.message);
                        $("#name").val(data.name);
                    }
                    else
                    error(data.message);
                }
            })
        })
   });
   
   
   
</script>
@include('users.footer')