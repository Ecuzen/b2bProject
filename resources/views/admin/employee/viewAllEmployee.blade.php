@include('admin.header')
<div class="row">
   <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">{{$tname}}</h4>
         </div>
         <div class="card-body p-4">
            <div class="table-responsive">
               <table id="file_export" class="table border table-striped table-bordered display text-nowrap">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>USERNAME</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>ACTION</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                     $i = 0;
                     @endphp
                     @foreach($allAdmin as $single)
                     <tr>
                        <td>{{++$i}}</td>
                        <td>{{$single->name}}</td>
                        <td>{{$single->username}}</td>
                        <td>{{$single->phone}}</td>
                        <td>{{$single->email}}</td>
                        <td><button class="btn btn-outline-primary w-10 view-previlege" type="button" data-id = "{{$single->aid}}">View Privilege</button> <button class="btn btn-outline-warning w-10 update-previlege" type="button" data-id = "{{$single->aid}}">Update Privilege</button> <button class="btn btn-outline-danger w-10 delete-employee" type="button" data-id = "{{$single->aid}}">Delete Employee</button></td>
                     </tr>
                     @endforeach
                  </tbody>
                  <tfoot>
                     <tr>
                        <th>#</th>
                        <th>NAME</th>
                        <th>USERNAME</th>
                        <th>PHONE</th>
                        <th>EMAIL</th>
                        <th>ACTION</th>
                     </tr>
                  </tfoot>
               </table>
            </div>
         </div>
      </div>
   </div>
   <div class="col-lg-6 previlege-section">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">Employee Privilege</h4>
         </div>
         <div class="container mt-5">
            <form id="add-employee-form">
               <div class="row">
                  <div class="col-12">
                     <div class="card mb-4">
                        <div class="card-header">
                           Members Privilege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="members-previlege" name="memberPrevilege" value="1">
                              <label class="form-check-label" for="members-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input members-previlege" type="checkbox" id="add-member" name="addMember" value="1">
                              <label class="form-check-label" for="add-member">Add Members</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input members-previlege" type="checkbox" id="list-member" name="listMember" value="1">
                              <label class="form-check-label" for="list-member">List Members</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input members-previlege" type="checkbox" id="user-request" name="userRequest" value="1">
                              <label class="form-check-label" for="user-request">User Request</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input members-previlege" type="checkbox" id="upgrade-member" name="upgradeMember" value="1">
                              <label class="form-check-label" for="upgrade-member">Upgrade Members</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input members-previlege" type="checkbox" id="user-complaints" name="userComplaints" value="1">
                              <label class="form-check-label" for="user-complaints">User Complaints</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           KYC Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="kyc-previlege" name="kycPrevilege" value="1">
                              <label class="form-check-label" for="kyc-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input kyc-previlege" type="checkbox" id="pendingProfileKyc" name="pendingProfileKyc" value="1">
                              <label class="form-check-label" for="pendingProfileKyc">Pending Profile KYC</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input kyc-previlege" type="checkbox" id="profileKyc" name="profileKyc" value="1">
                              <label class="form-check-label" for="profileKyc">Profile KYC</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input kyc-previlege" type="checkbox" id="aeps-kyc" name="aepsKyc" value="1">
                              <label class="form-check-label" for="aeps-kyc">AEPS KYC</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Payout Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="payout-previlege" name="payoutPrevilege" value="1">
                              <label class="form-check-label" for="payout-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input payout-previlege" type="checkbox" id="account-approval-request" name="accountApprovalRequest" value="1">
                              <label class="form-check-label" for="account-approval-request">Account Approval Request</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input payout-previlege" type="checkbox" id="pending-approval-request" name="pendingApprovalRequest" value="1">
                              <label class="form-check-label" for="pending-approval-request">Pending Approval Request</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Wallet Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="wallet-previlege" name="walletPrevilege" value="1">
                              <label class="form-check-label" for="wallet-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="wallet" name="wallet" value="1">
                              <label class="form-check-label" for="wallet">Wallet</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="credit-fund" name="creditFund" value="1">
                              <label class="form-check-label" for="credit-fund">Credit Fund</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="debit-fund" name="debitFund" value="1">
                              <label class="form-check-label" for="debit-fund">Debit Fund</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="pending-fund-request" name="pendingFundRequest" value="1">
                              <label class="form-check-label" for="pending-fund-request">Pending Fund Request</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="fund-request" name="fundRequest" value="1">
                              <label class="form-check-label" for="fund-request">Fund Request</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="lock-amount" name="lockAmount" value="1">
                              <label class="form-check-label" for="lock-amount">Lock Amount</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input wallet-previlege" type="checkbox" id="release-lock-amount" name="releaseLockAmount" value="1">
                              <label class="form-check-label" for="release-lock-amount">Release Lock Amount</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Transactions Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="transactions-previlege" name="transactionsPrevilege" value="1">
                              <label class="form-check-label" for="transactions-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="wallet-transactions" name="walletTransactions" value="1">
                              <label class="form-check-label" for="wallet-transactions">Wallet Transactions</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="aeps-icici" name="aepsIcici" value="1">
                              <label class="form-check-label" for="aeps-icici">AEPS ICICI</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="bbps" name="bbps" value="1">
                              <label class="form-check-label" for="bbps">BBPS</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="recharge" name="recharge" value="1">
                              <label class="form-check-label" for="recharge">Recharge</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="dmt" name="dmt" value="1">
                              <label class="form-check-label" for="dmt">DMT</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="payout" name="payout" value="1">
                              <label class="form-check-label" for="payout">Payout</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="quick-transfer" name="quickTransfer" value="1">
                              <label class="form-check-label" for="quick-transfer">Quieck Transfer</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="pan-registration" name="panRegistration" value="1">
                              <label class="form-check-label" for="pan-registration">PAN Registration</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input transactions-previlege" type="checkbox" id="pan-coupon" name="panCoupon" value="1">
                              <label class="form-check-label" for="pan-coupon">PAN Coupon</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Support Ticket Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="support-ticket-previlege" name="supportTicketPrevilege" value="1">
                              <label class="form-check-label" for="support-ticket-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input support-ticket-previlege" type="checkbox" id="pending-support-ticket" name="pendingSupportTicket" value="1">
                              <label class="form-check-label" for="pending-support-ticket">Pending Support Ticket</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input support-ticket-previlege" type="checkbox" id="support-ticket" name="supportTicket" value="1">
                              <label class="form-check-label" for="support-ticket">Support Ticket</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Settings Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="settings-previlege" name="settingsPrevilege" value="1">
                              <label class="form-check-label" for="settings-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="main-settings" name="mainSettings" value="1">
                              <label class="form-check-label" for="main-settings">Main Settings</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="package" name="package" value="1">
                              <label class="form-check-label" for="package">Package</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="commission-and-charges" name="commissionAndCharges" value="1">
                              <label class="form-check-label" for="commission-and-charges">Commission and Charges</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="regristration-fees" name="regristrationFees" value="1">
                              <label class="form-check-label" for="regristration-fees">Registration Fees</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="user-login-logs" name="userLoginLogs" value="1">
                              <label class="form-check-label" for="user-login-logs">User Login Logs</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input settings-previlege" type="checkbox" id="admin-login-logs" name="adminLoginLogs" value="1">
                              <label class="form-check-label" for="admin-login-logs">Admin Login Logs</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Other Services Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="other-service-previlege" name="otherServicePrevilege" value="1">
                              <label class="form-check-label" for="other-service-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input other-service-previlege" type="checkbox" id="add-service" name="addService" value="1">
                              <label class="form-check-label" for="add-service">Add Service</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input other-service-previlege" type="checkbox" id="list-service" name="listService" value="1">
                              <label class="form-check-label" for="list-service">List Service</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Manage Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="manage-previlege" name="managePrevilege" value="1">
                              <label class="form-check-label" for="manage-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input manage-previlege" type="checkbox" id="company-banks" name="companyBanks" value="1">
                              <label class="form-check-label" for="company-banks">Company Banks</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input manage-previlege" type="checkbox" id="manage-services" name="manageServices" value="1">
                              <label class="form-check-label" for="manage-services">Manage Services</label>
                           </div>
                        </div>
                     </div>
                     <div class="card mb-4">
                        <div class="card-header">
                           Employee Management Previlege
                        </div>
                        <div class="card-body">
                           <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="employee-management-previlege" name="employeeManagementPrevilege" value="1">
                              <label class="form-check-label" for="employee-management-previlege">Check All</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input employeeManagement" type="checkbox" id="add-employee" name="addEmployee" value="1">
                              <label class="form-check-label" for="add-employee">Add Employee</label>
                           </div>
                           <div class="form-check form-check-inline">
                              <input class="form-check-input employeeManagement" type="checkbox" id="view-employee" name="viewEmployee" value="1">
                              <label class="form-check-label" for="view-employee">View Employee</label>
                           </div>
                        </div>
                     </div>
                     <div class="mb-3 row submit-button">
                        <div class="col-lg-6">
                            <input type="hidden" id="adminId" name="adminId">
                           <button class="btn btn-outline-primary w-10" type="submit">Update Employee Previlege</button>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
var allEmployee = @json($allAdmin);
   var numericInputs = document.getElementsByClassName('numeric-input');
   for (var i = 0; i < numericInputs.length; i++) {
       numericInputs[i].addEventListener('input', function(event) {
           this.value = this.value.replace(/[^0-9]/g, '');
       });
   }
   $(document).ready(function(){
       $(".previlege-section").hide();
       $(".submit-button").hide();
   })
   $(document).ready(function() {
           $("#employee-management-previlege").change(function() {
               if (this.checked) {
                   $(".employeeManagement").prop("checked", true);
               } else {
                   $(".employeeManagement").prop("checked", false);
               }
           });
           $("#manage-previlege").change(function() {
               if (this.checked) {
                   $(".manage-previlege").prop("checked", true);
               } else {
                   $(".manage-previlege").prop("checked", false);
               }
           });
           $("#other-service-previlege").change(function() {
               if (this.checked) {
                   $(".other-service-previlege").prop("checked", true);
               } else {
                   $(".other-service-previlege").prop("checked", false);
               }
           });
           $("#settings-previlege").change(function() {
               if (this.checked) {
                   $(".settings-previlege").prop("checked", true);
               } else {
                   $(".settings-previlege").prop("checked", false);
               }
           });
           $("#support-ticket-previlege").change(function() {
               if (this.checked) {
                   $(".support-ticket-previlege").prop("checked", true);
               } else {
                   $(".support-ticket-previlege").prop("checked", false);
               }
           });
           $("#transactions-previlege").change(function() {
               if (this.checked) {
                   $(".transactions-previlege").prop("checked", true);
               } else {
                   $(".transactions-previlege").prop("checked", false);
               }
           });
           $("#wallet-previlege").change(function() {
               if (this.checked) {
                   $(".wallet-previlege").prop("checked", true);
               } else {
                   $(".wallet-previlege").prop("checked", false);
               }
           });
           $("#payout-previlege").change(function() {
               if (this.checked) {
                   $(".payout-previlege").prop("checked", true);
               } else {
                   $(".payout-previlege").prop("checked", false);
               }
           });
           $("#kyc-previlege").change(function() {
               if (this.checked) {
                   $(".kyc-previlege").prop("checked", true);
               } else {
                   $(".kyc-previlege").prop("checked", false);
               }
           });
           $("#members-previlege").change(function() {
               if (this.checked) {
                   $(".members-previlege").prop("checked", true);
               } else {
                   $(".members-previlege").prop("checked", false);
               }
           });
       });
   $("#add-employee-form").on('submit', function(e) {
   e.preventDefault();
   var form = new FormData(this); 
   form.append('_token','{{csrf_token()}}');
   $.ajax({
       url: "{{route('updateEmployeePrevilege')}}",
       method: 'post',
       data: form,
       contentType: false,
       processData: false, 
       success: function(data) {
           if (data.status === 'SUCCESS') {
               successReload(data.message);
           } else {
               $(".previlege-section").hide();
               error(data.message);
           }
       }
   });
   });
   $(".delete-employee").click(function(){
       $(".submit-button").hide();
       var $button = $(this);
       var previous = $button.html();
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
                $button.html("Waiting!!");
                $button.prop('disabled',true);
                $.ajax({
                   url : 'admin-remove-employee/'+$button.data("id"),
                   success:function(data)
                   {
                        $button.html(previous);
                        $button.prop('disabled',false);
                       if(data.status == 'SUCCESS')
                       successReload(data.message);
                       else
                       error(data.message);
                   }
               })
            }
          })
   });
   $(".view-previlege").click(function(){
       $(".submit-button").hide();
       $(".previlege-section").show();
       $("#add-employee-form :checkbox").prop('disabled', true);
       var $button = $(this);
       var id = $button.data("id");
       $.each(allEmployee, function (index, allEmployee) {
            if(id == allEmployee.aid)
            {
                $("#add-employee-form :checkbox").prop('checked', false);
                var keys = Object.keys(allEmployee);
                for(var i = 0; i < Object.keys(allEmployee).length; i++)
                {
                    if(allEmployee[keys[i]] == 1)
                    $("input[name='" + keys[i] + "']").prop("checked", true);
                }
            }
        });
   })
   $(".update-previlege").click(function(){
       $(".submit-button").show();
       $(".previlege-section").show();
       $("#add-employee-form :checkbox").prop('disabled', false);
       var $button = $(this);
       var id = $button.data("id");
       $("#adminId").val(id);
       $.each(allEmployee, function (index, allEmployee) {
            if(id == allEmployee.aid)
            {
                $("#add-employee-form :checkbox").prop('checked', false);
                var keys = Object.keys(allEmployee);
                for(var i = 0; i < Object.keys(allEmployee).length; i++)
                {
                    if(allEmployee[keys[i]] == 1)
                    $("input[name='" + keys[i] + "']").prop("checked", true);
                }
            }
        });
   })
</script>
<script src="{{url('assets')}}/dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="{{url('assets')}}/dist/js/datatable/datatable-advanced.init.js"></script>
@include('admin.footer')