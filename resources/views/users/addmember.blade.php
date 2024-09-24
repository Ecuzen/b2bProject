@include('users/header')
<div class="container-fluid">
   <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
         <div class="row align-items-center">
            <div class="col-9">
               <h4 class="fw-semibold mb-8">Add Member</h4>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                     <li class="breadcrumb-item" aria-current="page">Add Member</li>
                  </ol>
               </nav>
            </div>
            <div class="col-3">
               <div class="text-center mb-n5">  
                  <img src="{{url('assets')}}/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="widget-content searchable-container list">
      <!-- --------------------- start Contact ---------------- -->
      <div class="card card-body">
         <div class="row">
            <div class="col-md-4 col-xl-3">
               <form class="position-relative">
                  <input type="text" class="form-control product-search ps-5" id="input-search" placeholder="Search Members..." />
                  <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
               </form>
            </div>
            <div class="col-md-8 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
               <div class="action-btn show-btn" style="display: none">
                  <a href="javascript:void(0)" class="delete-multiple btn-light-danger btn me-2 text-danger d-flex align-items-center font-medium">
                  <i class="ti ti-trash text-danger me-1 fs-5"></i> Delete All Row 
                  </a>
               </div>
               <a onclick="$('#addContactModal').modal('show');" id="btn-add-contact" class="btn btn-info d-flex align-items-center">
               <i class="ti ti-users text-white me-1 fs-5"></i> Add Member 
               </a>
            </div>
         </div>
      </div>
      <!-- ---------------------
         end Contact
         ---------------- -->
      <!-- Modal -->
      <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
               <div class="modal-header d-flex align-items-center">
                  <h5 class="modal-title">Add Member</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  <div class="add-contact-box">
                     <div class="add-contact-content">
                        <form id="addContactModalTitle">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="mb-3 contact-name">
                                    <input type="text" id="m-name" class="form-control" placeholder="Name" />
                                    <span class="validation-text text-danger"></span>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3 contact-email">
                                    <input type="text" id="m-email" class="form-control" placeholder="Email" />
                                    <span class="validation-text text-danger"></span>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="mb-3 contact-phone">
                                    <input type="text" id="m-phone" class="form-control" placeholder="Phone" oninput="validateNumericInput(this)" maxlength="10"/>
                                    <span class="validation-text text-danger"></span>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="mb-3 contact-occupation">
                                    <!--<input type="text"  placeholder="Occupation" />-->
                                    <select id="m-role" class="form-control">
                                       <option value="0">select</option>
                                       @foreach($role as $role)
                                       <option value="{{$role->id}}">{{$role->name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <!--<div class="row">
                              <div class="col-md-12">
                                <div class="mb-3 contact-location">
                                  <input type="text" id="c-location" class="form-control" placeholder="Location" />
                                </div>
                              </div>
                              </div>-->
                        </form>
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button id="btn-add" class="btn btn-success rounded-pill px-4" onclick="addmember()">Add</button>
                  <!--<button id="btn-edit" class="btn btn-success rounded-pill px-4">Save</button>-->
                  <button class="btn btn-danger rounded-pill px-4" data-bs-dismiss="modal"> Discard </button>
               </div>
            </div>
         </div>
      </div>
      <div class="card card-body">
         <div class="table-responsive">
            <table class="table search-table align-middle text-nowrap">
               <thead class="header-item">
                  <th>
                     <div class="n-chk align-self-center text-center">
                        <div class="form-check">
                           <input type="checkbox" class="form-check-input primary" id="contact-check-all" />
                           <label class="form-check-label" for="contact-check-all"></label>
                           <span class="new-control-indicator"></span>
                        </div>
                     </div>
                  </th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Action</th>
               </thead>
               <tbody>
                  @foreach($list as $list)
                  <!-- start row -->
                  @if(isset($list->first))
                  <?php $name = $list->first."". $list->last; ?>
                  @else
                  <?php $name = $list->name; ?>
                  @endif
                  <tr class="search-items" id="">
                     <td>
                        <div class="n-chk align-self-center text-center">
                           <div class="form-check">
                              <input type="checkbox" class="form-check-input contact-chkbox primary" id="checkbox1" />
                              <label class="form-check-label" for="checkbox1"></label>
                           </div>
                        </div>
                     </td>
                     <td>
                        <div class="d-flex align-items-center">
                           <img src="{{url('assets')}}/dist/images/profile/user-1.jpg" alt="avatar" class="rounded-circle" width="35" />
                           <div class="ms-3">
                              <div class="user-meta-info">
                                 <h6 class="user-name mb-0" data-name="Emma Adams">{{$name}}</h6>
                                 <span class="user-work fs-3" data-occupation="Web Developer">{{$list->role_name}}</span>
                              </div>
                           </div>
                        </div>
                     </td>
                     <td>
                        <span class="usr-email-addr" data-email="adams@mail.com">{{$list->email}}</span>
                     </td>
                     <td>
                        <span class="usr-location" data-location="Boston, USA">{{ $list->mobile ?? $list->phone }}</span>
                     </td>
                     <td>
                        <div class="action-btn">
                           <div data-id = '{{$list->id}}' style="cursor: pointer;" class="text-info view-details" >
                              <i class="ti ti-eye fs-5"></i>
                           </div>
                           <!--<a href="javascript:void(0)" class="text-dark delete ms-2">
                              <i class="ti ti-trash fs-5"></i>
                              </a>-->
                        </div>
                     </td>
                  </tr>
                  <!-- end row -->
                  @endforeach
               </tbody>
            </table>
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
               View Member
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
         </div>
         <div class="modal-body" id="receipt-data">
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3 contact-name">
                      <label for="v-kyc">User Wallet</label>
                     <input type="text" id="v-wallet" style="border-color: transparent;" class="form-control" readonly=""/>
                     <span class="validation-text text-danger"></span>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="mb-3 contact-email">
                      <label for="v-kyc">User Status</label>
                     <input type="text" id="v-status" style="border-color: transparent;" class="form-control" readonly=""/>
                     <span class="validation-text text-danger"></span>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6">
                  <div class="mb-3 contact-name">
                     <label for="v-kyc">Profile Kyc</label>
                     <input type="text" id="v-kyc" style="border-color: transparent;" class="form-control" readonly=""/>
                     <span class="validation-text text-danger"></span>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect text-start" data-bs-dismiss="modal" > Close </button>
         </div>
      </div>
   </div>
</div>
<script>
   var userList = @json($allData);
       $(".view-details").click(function(){
           var $button = $(this);
           var id = $button.data('id');
           $.each(userList,function(index,user){
                if(user.id == id)
                {
                    $("#v-wallet").val(user.hasOwnProperty('wallet') ? user['wallet'] : '0');
                    $("#v-status").val(user.hasOwnProperty('active') ? user['active'] == 1?'Active':'Deactive' : 'Request Pending');
                    $("#v-kyc").val(user.active == 1?'Done Profile KYC':'Pending Profile Kyc');
                }
            });
           $("#pop-tool-modal").modal("show");
       })
       function validateNumericInput(input) {
         input.value = input.value.replace(/\D/g, '');
       }
       
       function addmember()
       {
           var phone = $("#m-phone").val();
           var name = $("#m-name").val();
           var email = $("#m-email").val();
           var role = $("#m-role").val();
           if(role == 0)
           {
               error('Please select a valid role');
               return;
           }
           if(phone.length != 10)
           {
               error('Please enter 10 digit mobile number');
               return;
           }
           if(!validateEmail(email))
           {
               error('Please enter valid email id');
               return;
           }
           if(name == "")
           {
               error('Please Enter Member name');
               return;
           }
           $.ajax({
               url : '/add-member',
               method : 'post',
               data : {
                   'name' : name,
                   'phone' : phone,
                   'email' : email,
                   'role' : role,
                   '_token' : '{{ csrf_token() }}',
               },
               success:function(data)
               {
                   if(data.status == 'SUCCESS')
                   {
                       successReload(data.msg);
                   }
                   else
                   {
                       error(data.msg);
                   }
               }
           })
       }
       function validateEmail(email) {
         var emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
         return emailPattern.test(email);
       }
</script>
@include('users/footer')