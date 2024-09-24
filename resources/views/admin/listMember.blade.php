@include('admin.header')
<div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">  
                                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Username</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Wallet</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
    
                                        @php
                                        $i=0;
                                        @endphp
                                        <tbody>
                                            @foreach($listMember as $member)
                                            <tr>
                                                <td>{{++$i}}</td>
                                                <td>{{$member->name}}</td>
                                                <td>{{$member->username}}</td>
                                                <td>{{$member->phone}}</td>
                                                <td>{{$member->email}}</td>
                                                <td>{{$member->roleName}}</td>
                                                <td>{{$member->wallet}}</td>
                                                <td><button class="btn btn-warning member-login" data-member="{{$member->id}}">Login</button> <button class="btn btn-info member-service" data-member = "{{$member->id}}">Service</button> <button class="btn btn-success member-view" data-member = "{{$member->id}}">View</button> <button class="btn btn-primary member-edit" data-member = "{{$member->id}}">Edit</button>
                                                @if($member->active == 1)
                                                    <button class="btn btn-danger change-user-status" data-member = "{{$member->id}}" data-type = 'deactivate'>Deactivate</button>
                                                @else
                                                    <button class="btn btn-success change-user-status" data-member = "{{$member->id}}" data-type = "activate">Activate</button>
                                                @endif
                                                </td>
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
                <h6 class="modal-title m-0" id="backDropmodalTitle">Center Modal</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                  
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
  $(document).ready(function() {
    $(document).on("click",'.member-login', function() {
      var $button = $(this);
      var memberId = $button.data("member");
      var originalText = $button.text();
      $button.text("Loading...");
      $button.prop('disabled',true);
      $.ajax({
          url : '/admin-member-login/'+memberId,
          success:function(data)
          {
                $button.text(originalText);
                $button.prop('disabled',false);
              if(data.status == 'SUCCESS')
              {
                    successRedir(data.message,"{{route('home')}}");
              }
              else
              {
                  error(data.message);
              }
          }
      })
    });
    
    $(document).on("click",'.member-service', function() {
      var $button = $(this);
      var memberId = $button.data("member");
      var originalText = $button.text();
      $button.text("Loading...");
      $button.prop('disabled',true);
      $.ajax({
          url : '/admin-member-service/'+memberId,
          success:function(data)
          {
                $button.text(originalText);
                $button.prop('disabled',false);
              if(data.status == 'SUCCESS')
              {
                   $("#backDropmodal").modal("show"); 
                   $("#backDropmodalTitle").html('Service List'); 
                   $(".backDropmodal-body").html(data.view); 
              }
              else
              {
                  error(data.message);
              }
          }
      })
    });
    
    $(document).on("click",'.member-view', function() {
      var $button = $(this);
      var memberId = $button.data("member");
      var originalText = $button.text();
      $button.text("Loading...");
      $button.prop('disabled',true);
      $.ajax({
          url : '/admin-member-view/'+memberId,
          success:function(data)
          {
                $button.text(originalText);
                $button.prop('disabled',false);
              if(data.status == 'SUCCESS')
              {
                    $("#backDropmodal").modal("show"); 
                    $("#backDropmodalTitle").html('View Member'); 
                    $(".backDropmodal-body").html(data.view);
              }
              else
              {
                  error(data.message);
              }
          }
      })
    });
    
    $(document).on("click",'.member-edit', function() {
        var $button = $(this);
        var memberId = $button.data("member");
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        $.ajax({
            url : '/admin-member-edit/'+memberId,
            success:function(data)
            {
                $button.text(originalText);
                $button.prop('disabled',false);
                if(data.status == 'SUCCESS')
                {
                    $("#backDropmodal").modal("show"); 
                    $("#backDropmodalTitle").html('Edit Member'); 
                    $(".backDropmodal-body").html(data.view);
                }
                else
                {
                  error(data.message);
                }
          }
      })
    })
    
    $(document).on("click",'.change-user-status', function() {
        var $button = $(this);
        var memberId = $button.data("member");
        var originalText = $button.text();
        $button.text("Loading...");
        $button.prop('disabled',true);
        var type = $button.data("type");
        $.ajax({
            url : '/change-user-status/'+type+'/'+memberId,
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
  });
</script>

@include('admin.footer')