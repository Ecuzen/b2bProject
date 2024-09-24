@include('users.header')
<div class="container-fluid">
   <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
         <div class="row align-items-center">
            <div class="col-9">
               <h4 class="fw-semibold mb-8">Wallet to wallet</h4>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                     <li class="breadcrumb-item" aria-current="page">wallet to wallet</li>
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
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="card-title">Wallet to wallet</h4>
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
                           <div id="initial-pos">
                               <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom01">USERNAME
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control " id="username"  placeholder="Username" required readonly="">
                                 <div class="invalid-feedback">
                                    Username
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom01">NAME
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control " id="name"  placeholder="Name" required readonly="">
                                 <div class="invalid-feedback">
                                    Name
                                 </div>
                              </div>
                           </div>
                           <div class="mb-3 row">
                              <label class="col-lg-4 col-form-label" for="validationCustom01">AMOUNT
                              <span class="text-danger">*</span>
                              </label>
                              <div class="col-lg-8">
                                 <input type="text" class="form-control numeric-input" id="amount"  placeholder="Enter Amount" required>
                                 <div class="invalid-feedback">
                                    Amount
                                 </div>
                              </div>
                           </div>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               <button class="btn btn-outline-primary w-100 transfer">Credit</button>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
       <div class="col-lg-12">
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
                              <h6 class="fs-4 fw-semibold mb-0">Sender</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Receiver</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Txn Id</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Amount</h6>
                           </th>
                           <th>
                              <h6 class="fs-4 fw-semibold mb-0">Date</h6>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                    @foreach($latestTxns as $txn)
                    <tr class="search-items">
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0" >{{$txn['senderName']}}</h6>
                              <span class="user-work fs-3" >{{$txn['senderPhone']}}</span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="ms-3">
                            <div class="user-meta-info">
                              <h6 class="user-name mb-0" >{{$txn['receiverName']}}</h6>
                              <span class="user-work fs-3" >{{$txn['receiverphone']}}</span>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="usr-email-addr" >{{$txn['txnid']}}</span>
                      </td>
                      <td>
                        <span class="usr-location" >{{$txn['amount']}}</span>
                      </td>
                      <td>
                        <span class="usr-ph-no" >{{$txn['date']}}</span>
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

<!--Payment Receipt modal-->
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
    $(document).ready(function(){
        $("#initial-pos").hide();
        $(".transfer").hide();
        $("#mobile").on('input',function(){
            var mobile = $("#mobile").val();
            if(mobile.length == 10)
            {
                $.ajax({
                    url : '/fetch-user-by-mobile/'+mobile,
                    success:function(data)
                    {
                        if(data.status == 'SUCCESS')
                        {
                            success(data.message);
                            $("#initial-pos").show();
                            $(".transfer").show();
                            $("#username").val(data.data.username);
                            $("#name").val(data.data.name);
                        }
                        else
                        {
                            info(data.message);
                            $("#initial-pos").hide();
                            $(".transfer").hide();
                            $("#username").val('');
                            $("#name").val('');
                        }
                    }
                })
            }
        })
        $(".transfer").click(function(){
            var mobile = $("#mobile").val();
            var amount = $("#amount").val();
            if(mobile.length != 10 || amount == "" || amount <= 0)
            {
                info('Please send valid data');
                return;
            }
            $.ajax({
                url : "/wallet-to-wallet-credit",
                method : 'post',
                data :{
                    "mobile" : mobile,
                    "amount" : amount,
                    "_token" : "{{csrf_token()}}"
                },
                success:function(data)
                {
                    if(data.status == "SUCCESS")
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
    })
</script>
@include('users.footer')