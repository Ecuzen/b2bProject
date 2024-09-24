@include('admin.header')
<div class="row">
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Add Company Account</h4>
         </div>
         <div class="card-body p-4">
            <div class="form-validation">
               <form class="needs-validation" novalidate >
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Account Number
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control numeric-input" id="add-account" placeholder="Account Number" required>
                              <div class="invalid-feedback">
                                 Account Number
                              </div>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">IFSC Code
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="add-ifsc" placeholder="IFSC Code" required>
                              <div class="invalid-feedback">
                                 IFSC Code
                              </div>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Bank Name
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="add-bank" placeholder="Bank Name" required>
                              <div class="invalid-feedback">
                                 Bank Name
                              </div>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Account Holder Name
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="add-name" placeholder="Account Holder Name" required>
                              <div class="invalid-feedback">
                                 Account Holder Name
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="mb-3 row">
               <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
               <div class="col-lg-6">
                  <button class="btn btn-outline-primary w-10 add-bank-account">Add Account</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Account List</h4>
         </div>
         <div class="card-body p-4">
            <table id="" class="table border table-striped table-bordered display text-nowrap">
               <thead>
                  <tr>
                     <th>#</th>
                     <th>Account Holder Name</th>
                     <th>Account Number</th>
                     <th>IFSC</th>
                     <th>Bank Name</th>
                     <th>Action</th>
                  </tr>
               </thead>
               @php
               $i=0
               @endphp
               <tbody>
                  @foreach($bankData as $bank)
                  <tr>
                     <td>{{++$i}}</td>
                     <td>{{$bank->name}}</td>
                     <td>{{$bank->account}}</td>
                     <td>{{$bank->ifsc}}</td>
                     <td>{{$bank->bank}}</td>
                     <td><button class="btn btn-warning update-account" data-id="{{$bank->id}}">Update</button> <button class="btn btn-danger delete-account" data-id="{{$bank->id}}" >Delete</button></td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="backDropmodal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="backDropmodalTitle">Update Bank Account</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label" for="validationCustom03">Account Holder Name</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="account-name" placeholder="Account Holder Name" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label" for="validationCustom03">Account Number</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control numeric-input" id="account-number" placeholder="Account Number" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label" for="validationCustom03">IFSC Code</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="ifsc" placeholder="IFSC Code" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label class="col-lg-4 col-form-label" for="validationCustom03">Bank Name</label>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="bank" placeholder="Bank Name" required>
                    </div>
                </div>
            </div>
            <input type="hidden" id="bankId">
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm lataest-update">Update</button>
                <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
var accounts = @json($bankData);
$(".update-account").click(function(){
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    var bankId = $button.data('id');
    $("#bankId").val(bankId);
    $.each(accounts, function (index, accounts) {
            if(bankId == accounts.id)
            {
                $("#account-name").val(accounts.name);
                $("#account-number").val(accounts.account);
                $("#ifsc").val(accounts.ifsc);
                $("#bank").val(accounts.bank);
            }
        });
    $("#backDropmodal").modal('show');
})
$(".delete-account").click(function(){
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    var bankId = $button.data('id');
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
      }).then(result => {
        if (result.isConfirmed) {
        $.ajax({
            url : '/admin-manage-bank-account/delete/'+bankId,
            success:function(data)
            {
                $button.text(originalText);
                $button.prop('disabled',false);
                if(data.status == 'SUCCESS')
                {
                    Swal.fire("Deleted!", data.message, "success");
                }
                else
                {
                    Swal.fire("Failed!", data.message, "error");
                }
            }
            
        })
        }
      });
})
$(".add-bank-account").click(function(){
    var account = $("#add-account").val();
    var ifsc = $("#add-ifsc").val();
    var bank = $("#add-bank").val();
    var name = $("#add-name").val();
    if(account == "" || ifsc == "" || bank == "" || name == "")
    {
        info('Please send all data');
        return;
    }
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    $.ajax({
        url : '/add-bank-account',
        method : 'post',
        data : {
            'account' : account,
            'ifsc' : ifsc,
            'bank' : bank,
            'name' : name,
            '_token' : '{{csrf_token()}}'
        },
        success:function(data)
        {
            $button.text(originalText);
            $button.prop('disabled',false);
            if(data.status == 'SUCCESS')
            successReload(data.message);
            else
            error(data.message);
        }
    })
})
$(".lataest-update").click(function(){
    var bankId = $("#bankId").val();
    var name = $("#account-name").val();
    var account = $("#account-number").val();
    var ifsc = $("#ifsc").val();
    var bank = $("#bank").val();
    if(name == "" || account == "" || ifsc == "" || bank == "" || bankId == "")
    {
        info('All input fields are required!');
        return;
    }
    var $button = $(this);
    var originalText = $button.text();
    $button.text("Loading...");
    $button.prop('disabled',true);
    $.ajax({
        url : '/admin-manage-bank-account/update/'+bankId,
        method : 'post',
        data : {
            'account' : account,
            'name' : name,
            'ifsc' : ifsc,
            'bank' : bank,
            '_token' : '{{csrf_token()}}'
        },
        success:function(data)
        {
            $button.text(originalText);
            $button.prop('disabled',false);
            if(data.status == 'SUCCESS')
            {
                successReload(data.message);
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
@include('admin.footer')