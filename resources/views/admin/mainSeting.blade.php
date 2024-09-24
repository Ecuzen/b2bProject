@include('admin.header')
<div class="row">
    <div class="col-3"></div>
<div class="col-6">
   <div class="card">
      
      <div class="card-body">
          @foreach($settingData as $data)
            <form id="form_{{$data->name}}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                <div class="form-group row">
                   <label for="inputName1" class="col-md-3 ">{{strtoupper($data->name)}}</label>
                   <div class="col-md-9">
                      <div class="row">
                         <div class="col-8">
                            <input type="{{$data->type}}" value="{{$data->value}}" name="{{$data->name}}" class="form-control">
                         </div>
                         <div class="col-4">
                            <button type="button"  class="btn btn-success update"  data-name = "{{$data->name}}" data-type = "{{$data->type}}">UPDATE</button>
                         </div>
                      </div>
                   </div>
                </div>
             </form>
          @endforeach
      </div>
   </div>
</div>
</div>
<script>
    $(".update").click(function(){
        var $button = $(this);
        var form =  $('#form_'+$button.data('name'));
        if (form) {
        const formData = new FormData(form[0]);
        formData.append('_token', '{{csrf_token()}}');
        formData.append('fieldName', $button.data('name'));
        formData.append('fieldType', $button.data('type'));
        $button.html('Waiting!!');
        $button.prop('disabled',true);
        $.ajax({
          type: 'POST',
          url: '/submit-manage-setting',
          data: formData,
          processData: false,
          contentType: false,
          success: function (data) {
            if(data.status == 'SUCCESS')
            {
                successReload(data.message);
            }
            else
            {
                error(data.message);
            }
          }
        });
      }
    })
</script>
@include('admin.footer')