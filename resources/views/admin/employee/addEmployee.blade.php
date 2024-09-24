@include('admin.header')
<div class="row">
    <div class="col-lg-6 ">
      <div class="card ">
         <div class="card-header">
            <h4 class="card-title">{{$tname}}</h4>
         </div>
         <div class="card-body p-4">
            <div class="form-validation">
               <form class="needs-validation" novalidate >
                  <div class="row">
                     <div class="col-xl-12">
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Employee Name
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="employee-name" placeholder="Employee Name" required>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Mobile Number
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control numeric-input" id="employee-mobile" placeholder="Mobile Number" required maxlength="10">
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Email Id
                           </label>
                           <div class="col-lg-8">
                              <input type="email" class="form-control " id="employee-email" placeholder="Email Id" required>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Create Username
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="create-username" placeholder="Create Username" required>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Create Password
                           </label>
                           <div class="col-lg-8">
                              <input type="text" class="form-control " id="create-password" placeholder="Create Password" required>
                           </div>
                        </div>
                        <div class="mb-3 row">
                           <label class="col-lg-4 col-form-label" for="validationCustom03">Confirm Password
                           </label>
                           <div class="col-lg-8">
                              <input type="password" class="form-control " id="confirm-password" placeholder="Confirm Password" required>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
            <div class="mb-3 row">
               <label class="col-lg-3 col-form-label" for="validationCustom02"></label>
               <div class="col-lg-6">
                  <button class="btn btn-outline-primary w-10 add-employee-next">Next Step</button>
               </div>
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
                <div class="mb-3 row">
                    <div class="col-lg-6">
                        <button class="btn btn-outline-primary w-10" type="submit">Add Employee</button>
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
    var numericInputs = document.getElementsByClassName('numeric-input');
    for (var i = 0; i < numericInputs.length; i++) {
        numericInputs[i].addEventListener('input', function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    $(document).ready(function(){
        $(".previlege-section").hide();
    })
    var employeeData;
    $(".add-employee-next").click(function(){
        var name = $("#employee-name").val();
        var mobile = $("#employee-mobile").val();
        var email = $("#employee-email").val();
        var username = $("#create-username").val();
        var createPassword = $("#create-password").val();
        var confirmPassword = $("#confirm-password").val();
        // if(name == "" || mobile == "" || mobile.length != 10 || email == "" || username == "" || createPassword == "" || createPassword !== confirmPassword)
        // {
        //     info('Please share all data');
        //     return;
        // }
        employeeData = {
            'name' : name,
            'mobile' : mobile,
            'email' : email,
            'username' : username,
            'password' : confirmPassword
        }
    $(".previlege-section").show();
    })
    $(".add-employee").click(function(){
        console.log(employeeData);
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
    form.append('employeeData',JSON.stringify(employeeData));
    $.ajax({
        url: '/add-employee',
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

</script>
@include('admin.footer')