@include('users.header')

<style>
    .loading {
 --speed-of-animation: 0.9s;
 --gap: 6px;
 --first-color: #4c86f9;
 --second-color: #49a84c;
 --third-color: #f6bb02;
 --fourth-color: #f6bb02;
 --fifth-color: #2196f3;
 display: flex;
 justify-content: center;
 align-items: center;
 width: 100px;
 gap: 6px;
 height: 100px;
}

.loading span {
 width: 4px;
 height: 50px;
 background: var(--first-color);
 animation: scale var(--speed-of-animation) ease-in-out infinite;
}

.loading span:nth-child(2) {
 background: var(--second-color);
 animation-delay: -0.8s;
}

.loading span:nth-child(3) {
 background: var(--third-color);
 animation-delay: -0.7s;
}

.loading span:nth-child(4) {
 background: var(--fourth-color);
 animation-delay: -0.6s;
}

.loading span:nth-child(5) {
 background: var(--fifth-color);
 animation-delay: -0.5s;
}

@keyframes scale {
 0%, 40%, 100% {
  transform: scaleY(0.05);
 }

 20% {
  transform: scaleY(1);
 }
}
</style>

<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">{{$header}}</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">{{strtoupper($type)}}</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/psa-logo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
              <div  class="col-lg-3"></div>
            <div class="col-lg-6 ">
              <div class="card ">
                   <div class="card-header">
                        <h4 class="card-title">{{strtoupper($type)}}</h4>
                    </div>
                <div class="card-body p-4">
                    <div class="form-validation">
                                         <form class="needs-validation" novalidate >
                                <div class="row">
                                    <div class="col-xl-12">
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom02">By Months<!--<span
                                                    class="text-danger">(optional)</span>-->
                                            </label>
                                            <div class="col-lg-9">
                                                <select class="form-control" id="timePeriod">
                                                    <option value="1">Last one Month</option>
                                                    <option value="2">Last six Months</option>
                                                    <option value="3">Custom Date</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <!--Date Picker for custom date-->
                                        <div class="mb-3 row" id="dateDiv">
                                            <label class="col-lg-3 col-form-label datepicker" for="validationCustom03" >Date From</label>
                                            <div class="col-lg-3">
                                                <input type="date" class="form-control" id="dateFrom"  required>
                                            </div>
                                            <label class="col-lg-3 col-form-label datepicker" for="validationCustom03">Date To</label>
                                            <div class="col-lg-3">
                                                <input type="date" class="form-control" id="dateTo" required>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom03">Amount From
                                            </label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control numeric-input" id="amountFrom" placeholder="Amount" required>
												<div class="invalid-feedback">
													Enter account number
												</div>
                                            </div>
                                            <label class="col-lg-3 col-form-label" for="validationCustom03">Amount To
                                            </label>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control numeric-input" id="amountTo" placeholder="Amount" required>
												<div class="invalid-feedback">
													Enter account number
												</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom02">Select Order</label>
                                            <div class="col-lg-9">
                                                <select class="form-control" id="orderType">
                                                    <option value="1">Desc.</option>
                                                    <option value="2">Asc.</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 row">
                                            <label class="col-lg-3 col-form-label" for="validationCustom02">Select type</label>
                                            <div class="col-lg-9">
                                                <select class="form-control" id="txnType">
                                                    <option value="1">All</option>
                                                    @if(strtoupper($type) == 'WALLET' || strtoupper($type) == 'WALLET-TO-WALLET')
                                                        <option value="CREDIT">CREDIT</option>
                                                        <option value="DEBIT">DEBIT</option>
                                                    @else
                                                        <option value="SUCCESS">SUCCESS</option>
                                                        <option value="FAILED">FAILED</option>
                                                        @if(strtoupper($type) != 'RECHARGE')
                                                            <option value="ERROR">ERROR</option>
                                                            <option value="PENDING">PENDING</option>
                                                        @endif
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </form>
                        </div>
                  <button class="btn btn-outline-primary w-100 fetch-transactions">Fetch Transactions</button>
                </div>
              </div> 
            </div>
          </div>
            <section id="transactions">
            
          </section>
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
                    <div class="loading">
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                </center>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal"> Close </button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="transactions-receipt-modal" tabindex="-1" role="dialog" aria-labelledby="transactions-receipt-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transactions-receipt-modalLabel">Receipt</h5>
            </div>
            <div class="modal-body" id="receipt-data">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary text-danger font-medium waves-effect text-start  printButton print-page"><i class="ti ti-printer fs-5"></i> Print </button>
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>






<script>
    $(document).ready(function(){
        $("#dateDiv").hide();

    })
    $("#timePeriod").on('change',function(){
        var filter = $("#timePeriod").val();
        if(filter == '3')
        {
            $("#dateDiv").show();
        }
        else
        {
            $("#dateDiv").hide();
        }
    })
    
    $(".fetch-transactions").click(function(){
        var from,to;
        var currentDate = new Date();
        var to = currentDate.toISOString().split('T')[0];
        var timePeriod = $("#timePeriod").val();
        timePeriod=Number(timePeriod);
        switch (timePeriod) {
          case 1:
            currentDate.setMonth(currentDate.getMonth() - 1);
            from = currentDate.toISOString().split('T')[0];
            break;
          case 2:
            currentDate.setMonth(currentDate.getMonth() - 6);
            from= currentDate.toISOString().split('T')[0];
            break;
          case 3:
                to = $("#dateTo").val();
                from = $("#dateFrom").val();
              break;
          default:
            info('Please provide some value for time period');
        }
        if(to == "" || from == "")
        {
            info('Please provide some value for time period');
            return;
        }
        
    var amountFrom = $("#amountFrom").val();
    var amountTo = $("#amountTo").val();
    if( (amountFrom != "" && amountTo=="") || (amountTo != "" && amountFrom == ""))
    {
        info('Please send both amount fields!');
        return;
    }
    var orderType = $("#orderType").val();
    var txnType = $("#txnType").val();
    $("#staticBackdrop").modal("show");
    $.ajax({
        url : '/fetch-transaction/{{$type}}',
        method : 'get',
        data : {
            "from" : from,
            "to" : to,
            "amountTo" : $("#amountTo").val(),
            "amountFrom" : $("#amountFrom").val(),
            "orderType" : $("#orderType").val(),
            "txnType" : $("#txnType").val(),
        },
        success:function(data)
        {
            $("#staticBackdrop").modal("hide");
           if(data.status == 'SUCCESS')
           {
               toastMsg(data.message);
               $("#transactions").html(data.view);
           }
        }
    })
    })
    
    
    
    
    
    
    
    
    
    
      $(document).ready(function () {
          $(document).on("click", ".transactions-receipt", function () {
           $(".transactions-receipt").click(function () {
            var $button = $(this);
            var txnid = $button.data('transactionid');
            if (txnid.length <= 4) {
                info('No transactions found');
                return;
            }

            var previous = $button.html();

            $button.html("Loading!!");
            $button.prop('disabled', true);
            $.ajax({
                url: '/transactions-receipt-details/{{$type}}/' + txnid,
                success: function (data) {
                    $button.html(previous);
                    $button.prop('disabled', false);
                    if (data.status == 'SUCCESS') {
                        $("#transactions-receipt-modal").modal("show");
                        $("#receipt-data").html(data.view);
                    } else
                        info(data.message);
                }
            })
        })
    })
    
    
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
})


</script>



@include('users.footer')