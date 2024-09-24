@include('users.header')
<div class="container-fluid">
   <div class="card bg-light-info shadow-none position-relative overflow-hidden">
      <div class="card-body px-4 py-3">
         <div class="row align-items-center">
            <div class="col-9">
               <h4 class="fw-semibold mb-8">Account Setting</h4>
               <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                     <li class="breadcrumb-item"><a class="text-muted" href="index.html">Home</a></li>
                     <li class="breadcrumb-item" aria-current="page">Account Setting</li>
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
   <div class="card">
      <div class="card-body">
         <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-account" role="tabpanel" aria-labelledby="pills-account-tab" tabindex="0">
               <div class="row">
                  <div class="col-lg-6 d-flex align-items-stretch">
                     <form id="profile-upload">
                        <div class="card w-100 position-relative overflow-hidden">
                           <div class="card-body p-4">
                              <h5 class="card-title fw-semibold">Change Profile</h5>
                              <p class="card-subtitle mb-4">Change your profile picture from here</p>
                              <div class="text-center">
                                 <img src="{{$profile}}" alt="" class="img-fluid rounded-circle" width="120" height="120">
                                 <div class="d-flex align-items-center justify-content-center my-4 gap-3">
                                     <input type="file" id="profile" name="profile" style="display: none;" accept="image/*">
                                    <button class="btn btn-primary profile-upload" type="button">Upload</button>
                                 </div>
                                 <p class="mb-0">(Allowed JPEG, JPG, GIF or PNG. Max size of 1MB)</p>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <div class="col-lg-6 d-flex align-items-stretch">
                     <div class="card w-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                           <h5 class="card-title fw-semibold">Personal Details</h5>
                           <p class="card-subtitle mb-4">To change your personal detail , edit and save from here</p>
                           <div class="mb-4">
                              <label for="name" class="form-label fw-semibold">Name</label>
                              <input type="text" class="form-control" id="name" value="{{$name}}">
                           </div>
                           <div class="mb-4">
                              <label for="phone" class="form-label fw-semibold">Phone</label>
                              <input type="text" class="form-control numeric-input" id="phone" value="{{$phone}}" maxlength="10">
                           </div>
                           <div class="mb-4">
                              <label for="email" class="form-label fw-semibold">Email</label>
                              <input type="email" class="form-control" id="email" value="{{$email}}">
                           </div>
                           <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                              <button class="btn btn-primary profile-update" type="button">Save</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-stretch">
                     <div class="card w-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                           <h5 class="card-title fw-semibold">Change PIN</h5>
                           <p class="card-subtitle mb-4">To change your pin please confirm here</p>
                           <div class="mb-4">
                              <label for="currentpin" class="form-label fw-semibold">Current PIN</label>
                              <input type="text" class="form-control numeric-input" id="currentpin" maxlength="4">
                           </div>
                           <div class="mb-4">
                              <label for="newpin" class="form-label fw-semibold">New PIN</label>
                              <input type="text" class="form-control numeric-input" id="newpin" maxlength="4">
                           </div>
                           <div class="mb-4">
                              <label for="confirmpin" class="form-label fw-semibold">Confirm PIN</label>
                              <input type="text" class="form-control numeric-input" id="confirmpin" maxlength="4">
                           </div>
                           <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                              <button class="btn btn-primary update-pin" type="button">Save</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-stretch">
                     <div class="card w-100 position-relative overflow-hidden">
                        <div class="card-body p-4">
                           <h5 class="card-title fw-semibold">Change Password</h5>
                           <p class="card-subtitle mb-4">To change your password please confirm here</p>
                           <div class="mb-4">
                              <label for="currentpassword" class="form-label fw-semibold">Current Password</label>
                              <input type="text" class="form-control" id="currentpassword" >
                           </div>
                           <div class="mb-4">
                              <label for="newpassword" class="form-label fw-semibold">New Password</label>
                              <input type="text" class="form-control" id="newpassword" >
                           </div>
                           <div class="mb-4">
                              <label for="confirmpassword" class="form-label fw-semibold">Confirm Password</label>
                              <input type="password" class="form-control" id="confirmpassword" >
                           </div>
                           <div class="d-flex align-items-center justify-content-end mt-4 gap-3">
                              <button class="btn btn-primary update-password" type="button">Save</button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>

   $(document).ready(function(){
       $(".profile-update").click(function(){
           var name = $("#name").val();
           var phone = $("#phone").val();
           var email = $("#email").val();
           if(name == "" || phone == "" || email == "")
           {
               info('All input fields are required');
               return;
           }
           var $button = $(this);
           var previous = $button.html();
           $button.html('Waiting!!');
           $button.prop('disabled',true);
           $.ajax({
               url : '/user-update/details',
               method : 'post',
               data :{
                   "name" : name,
                   "phone" : phone,
                   "email" : email,
                   "_token" : "{{csrf_token()}}"
               },
               success:function(data)
               {
                   $button.html(previous);
                   $button.prop('disabled',false);
                   if(data.status == "SUCCESS")
                   successReload(data.message);
                   else
                   error(data.message);
               }
           })
       })
       $(".update-pin, .update-password").click(function(event) {
           var clickedElement = event.target;
           if ($(clickedElement).hasClass("update-pin")) {
               var type = 'pin';
           }
           else{
               var type = 'password';
           }
           var current = $("#current"+type).val();
           var newt = $("#new"+type).val();
           var confirm = $("#confirm"+type).val();
           if(current == "" || newt =="" || confirm =="")
           {
               info('Please send Valid Data!!');
               return;
           }
           if(newt != confirm)
           {
               error('New '+type+' and confirm '+type+' did not match');
               return;
           }
           var $button = $(this);
           var previous = $button.html();
           var newdata = "new"+type;
           
           
           
           var dataFields = {};
               dataFields["new" + type] = newt;
               dataFields["current" + type] = current;
               dataFields['_token'] = "{{csrf_token()}}";
           
           $button.html('Waiting!!');
           $button.prop('disabled',true);
           $.ajax({
               url : '/user-update/'+type,
               method : 'post',
               data :dataFields,
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
       });
       $(".profile-upload").click(function(){
           $("#profile").click();
           $("#profile").change(function(){
               $("#profile-upload").submit();
           })
       });
       $("#profile-upload").submit(function(e){
                   e.preventDefault();
                    var formData = new FormData(this);
                    var csrfToken = "{{ csrf_token() }}";
                    formData.append('_token', csrfToken);
                   $.ajax({
                       url : '/user-update/profile',
                       method : 'post',
                       data :formData,
                       contentType: false,
                       processData: false,
                       success:function(data)
                       {
                           if(data.status == 'SUCCESS')
                           successReload(data.message);
                           else
                           error(data.message);
                       }
                   })
               });
   })
   
   
   var numericInputs = document.getElementsByClassName('numeric-input');
   
   
   for (var i = 0; i < numericInputs.length; i++) {
       numericInputs[i].addEventListener('input', function(event) {
           this.value = this.value.replace(/[^0-9]/g, '');
       });
   }
   
   
</script>
@include('users.footer')