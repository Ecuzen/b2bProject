
<div class="mb-5">
    <h2 class="fw-bolder fs-7 mb-3">Forgot your {{$type}}?</h2>
        <p class="mb-0 ">   
            Please enter the email address associated with your account and We will email you a link to reset your password.                
        </p>
</div>
<form id="send-for-reset">
    @csrf
    <div class="mb-3">
        <label for="femailid" class="form-label">Email address</label>
        <input type="email" class="form-control" id="femailid" aria-describedby="emailHelp">
    </div>
    <button type="submit" class="btn btn-primary w-100 py-8 mb-3">Forgot {{ucfirst($type)}}</button>
</form>
    <a href="/" class="btn btn-light-primary text-primary w-100 py-8">Back to Login</a>
<script>
    $(document).ready(function(){
        $("#send-for-reset").on('submit',function(e){
            e.preventDefault();
            var email = $("#femailid").val();
            var check = validateEmail(email);
            if(!check)
            {
                error('Please enter valid email');
                return;
            }
            $.ajax({
                url : '/forgot-pass-mail',
                method : 'POST',
                data : {
                    '_token' : '{{ csrf_token() }}',
                    'email' : email,
                    'type' : '{{$type}}'
                },
                success:function(data)
                {
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
        function validateEmail(email) {
          var emailPattern = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
          return emailPattern.test(email);
        }
    })
</script>