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
                        <th>Type</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$ticket->name}}</td>
                            <td>{{$ticket->username}}</td>
                            <td>{{$ticket->service}}</td>
                            <td>{{$ticket->message}}</td>
                            <td>{{$ticket->date}}</td>
                            <td><button class="btn btn-warning ticket-reply" data-id = "{{$ticket->id}}">Reply</button> </td>
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
                <h6 class="modal-title m-0" id="backDropmodalTitle">Tickets Reply</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                <div class="mb-3 row">
                    <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Reply</label>
                    <div class="col-sm-10">
                        <textarea class="form-select admin-message" >
                            
                        </textarea>
                        <input type="hidden" id="requestId" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-success btn-sm text-dark reply">Reply</button>
                <button type="button" class="btn btn-soft-secondary btn-sm text-warning" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    // $(".ticket-reply").click(function()
    $(document).on("click",'.ticket-reply', function()
    {
        var $button = $(this);
        $("#requestId").val($button.data('id'));
        $("#backDropmodal").modal('show');
    })
    // $(".reply").click(function()
    $(document).on("click",'.reply', function()
    {
        var msg = $(".admin-message").val().trim();
        if(msg == "" )
        {
            msg = 'NA';
        }
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/close-user-ticket',
            method : 'post',
            data : {
                "id" : $("#requestId").val(),
                "msg" : msg,
                '_token' : "{{csrf_token()}}"
            },
            success:function(data)
            {
                $button.html(originalText);
                $button.prop("disabled", false);
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
</script>
@include('admin.footer')