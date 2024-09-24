<!DOCTYPE html>
<html>
   <head>
      <style>
         body {
         font-family: Arial, sans-serif;
         }
         .container {
         max-width: 500px;
         margin: 0 auto;
         padding: 20px;
         border: 1px solid #ccc;
         border-radius: 5px;
         }
         h1 {
         text-align: center;
         }
         .info {
         margin-top: 20px;
         }
         .info p {
         margin: 5px 0;
         }
      </style>
   </head>
   <body>
      <div class="container">
         <div class="info">
            <form id="userEditSubmit">
               <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="name" placeholder="Name" value="{{$name}}">
               </div>
               <div class="form-group">
                  <label for="exampleInputEmail1">Email address</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email" placeholder="Email id" value="{{$email}}">
               </div>
               <div class="form-group">
                  <label for="exampleInputPassword1">Phone</label>
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Phone Number" name="phone" value="{{$phone}}">
               </div>
               <input type="hidden" value="{{$id}}" name="uid">
               <button type="submit" class="btn btn-primary">Submit</button>
            </form>
         </div>
      </div>
   </body>
</html>
<script>
    $("#userEditSubmit").on('submit',function(e){
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        $.ajax({
            url : '/admin-update-user-details',
            method :'post',
            data:formData,
                processData:false,
                contentType:false,
                cache:false,
                success:function(data)
                {
                    if(data.status == 'SUCCESS')
                    successReload(data.message);
                    else
                    error(data.message);
                }
            })
    })
</script>