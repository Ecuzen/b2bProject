@include('admin.header')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        color: #000!important;
    }
    
    .select2-container {
  width: 100% !important;
}
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                
            </div><!--end card-header-->
            <form method="post" id="notification-form">
            <div class="card-body">  
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Title</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" placeholder="Event Title" name="title">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-email-input" class="col-sm-2 form-label align-self-center mb-lg-0 text-end">URL</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="url" placeholder="Redirect Url" name="url">
                            </div>
                        </div>
                         
                        
                        <div class="mb-3 row">
                            <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Message</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="10" placeholder="Message" name="message"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">All Users</label>
                            <div class="col-sm-10">
                                <input type="radio" value="1" name="role" checked="" >   All users<br>
                                <input type="radio" value="2" name="role" >   Select User
                            </div>
                        </div>
                        
                        <div class="mb-3 row" id="multiple-select-user">
                            <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Select User</label>
                            <div class="col-sm-10">
                                <select class="form-control select-user" multiple="multiple" name="users[]">
                                    @foreach($users as $userId => $user)
                                        <option value="{{$userId}}">{{ ucfirst($user)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                         
                        <div class="mb-3 mb-lg-0 row">
                            <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end"></label>
                            <div class="col-sm-10">
                                <button class="btn btn-success" type="submit">Send Notification</button>
                            </div>
                        </div>                                 
                    </div>
                </div>                                                                      
            </div><!--end card-body-->
            </form>
        </div><!--end card-->
    </div><!--end col-->
</div>                 
<script>
    $(document).ready(function(){
        $(".select-user").select2();
        $('#multiple-select-user').hide();
        $('input[name="role"]').click(function()
        {
            let radioVal = $(this).val();
            if(radioVal == 1)
            {
               $('#multiple-select-user').hide(); 
            }
            else if(radioVal == 2)
            {
                $('#multiple-select-user').show(); 
            }
        })
        $("#notification-form").on('submit',function(e){
            e.preventDefault();
            var formData = new FormData(this);
              formData.append('_token', '{{ csrf_token() }}');
              $.ajax({
            	type: 'post',
            	url: "{{route('notification.store')}}",
            	data:   formData,
                         processData:false,
                         contentType:false,
                         cache:false,
                         async:false,
            	success: function (data) 
            	{
            	    if(data.status == 'SUCCESS')
            	    {
            	        success(data.message);
            	    }
            	    else
            	    {
            	        error(data.message);
            	    }
            	}
              });
        })
    });
</script>
@include('admin.footer')