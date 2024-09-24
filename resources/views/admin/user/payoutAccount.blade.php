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
                        <th>Acc. Holder name</th>
                        <th>Account No.</th>
                        <th>IFSC</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($accounts as $account)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$account->user_name}}</td>
                            <td>{{$account->username}}</td>
                            <td>{{$account->phone}}</td>
                            <td>{{$account->name}}</td>
                            <td>{{$account->account}}</td>
                            <td>{{$account->ifsc}}</td>
                            <td>
                                @if($account->status == 'PENDING')
                                <button class="btn btn-success account-approve" data-id = "{{$account->id}}">Approve</button> <button class="btn btn-danger account-reject" data-id = "{{$account->id}}">Reject</button> 
                                @else
                                <button class="btn btn-success " >Approved</button> <button class="btn btn-danger account-delete" data-id = "{{$account->id}}">Delete</button> 
                                @endif
                                <button class="btn btn-info view-passbook" data-url = "{{$account->passbook}}">View passbook</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
<script>
    // $(".view-passbook").click(function()
     $(document).on("click",'.view-passbook', function()
    {
    var $button = $(this);
    var url = $button.data('url');
    window.open(url, "_blank");
    })
    // $(".account-approve").click(function()
     $(document).on("click",'.account-approve', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/approve-account/'+$button.data('id'),
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
    // $(".account-reject").click(function()
     $(document).on("click",'.account-reject', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/reject-payout-account/'+$button.data('id'),
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
    // $(".account-delete").click(function()
     $(document).on("click",'.account-delete', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/payout-account-delete/'+$button.data('id'),
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
</script>
@include('admin.footer')