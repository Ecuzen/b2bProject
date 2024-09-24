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
                        <th>User Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Wallet</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->username}}</td>
                            <td>{{$user->phone}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->wallet}}</td>
                            <td>
                                @if($type == 'credit')
                                    <button class="btn btn-success fund-activity" data-id="{{$user->id}}">Credit</button>
                                @else
                                    <button class="btn btn-danger fund-activity" data-id="{{$user->id}}">Debit</button>
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
                <h6 class="modal-title m-0" id="backDropmodalTitle">{{$tname}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                <div class="mb-3 row">
                    <label class="col-sm-2 form-label align-self-center mb-lg-0 text-end">Amount</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control amount">
                        <input type="hidden" id="userId" >
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($type == 'credit')
                    <button type="button" class="btn btn-soft-success btn-sm text-dark fund">Credit</button>
                @else
                    <button type="button" class="btn btn-soft-danger btn-sm text-dark fund">Debit</button>
                @endif
                <button type="button" class="btn btn-soft-secondary btn-sm text-warning" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
//   $(".fund").click(function()
   $(document).on("click",'.fund', function()
   {
        var $button = $(this);
        var originalText = $button.text();
        var amount = $(".amount").val();
        if(amount == "" || amount <= 1)
        {
            info('Please enter an valid amount!');
            return;
        }
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/wallet-fund/'+"{{$type}}",
            method : 'post',
            data : {
                'id' : $("#userId").val(),
                'amount' : amount,
                '_token' : '{{csrf_token()}}'
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
//   $(".fund-activity").click(function()
   $(document).on("click",'.fund-activity', function() 
   {
       var $button = $(this);
       $("#userId").val($button.data('id'));
       $("#backDropmodal").modal("show");
   })
</script>
@include('admin.footer')