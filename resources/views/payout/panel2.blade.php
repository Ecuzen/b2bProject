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
                           <form id="excel-upload-form" enctype="multipart/form-data">
                                <input type="file" name="excel_file" id="excel_file">
                                <button type="submit">Upload Excel</button>
                            </form>              
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
            <div class="modal-footer">
                <button onclick="downloadFunction()" class="btn btn-blue text-white w-100" data-bs-toggle="modal" >Print</button>
                <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Function to handle download
    function downloadFunction() {
        hideButtons();
        const element = document.getElementById('body');
        const formattedDate = new Date().toLocaleDateString('en-GB').replace(/\//g, '-');
        const options = {
            margin: 10,
            filename: 'FileName_' + formattedDate + '.pdf',
            image: { type: 'jpeg', quality: 1.0 },
            html2canvas: { scale: 4 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        html2pdf(element, options);
        showButtons();
    }
</script>
<script>



   $('#excel-upload-form').submit(function(event) {
    event.preventDefault();
    
    var formData = new FormData($(this)[0]);
    
    var csrfToken = "{{ csrf_token() }}";
    formData.append('_token', csrfToken);

    $.ajax({
        url: '/upload-excel',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            // Handle success response
            console.log(response);
        },
        error: function () {
            // Handle error
            console.log("Error occurred while uploading excel.");
        }
    });
});



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



</script>
@include('users.footer')