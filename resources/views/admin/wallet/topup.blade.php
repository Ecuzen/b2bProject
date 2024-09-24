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
                        <th>Account</th>
                        <th>IFSC</th>
                        <th>Bank</th>
                        <th>Amount</th>
                        <th>Txn Id</th>
                        <th>RRN</th>
                        <th>Txn Date</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($topups as $topup)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$topup->name}}</td>
                            <td>{{$topup->username}}</td>
                            <td>{{$topup->phone}}</td>
                            <td>{{$topup->account}}</td>
                            <td>{{$topup->ifsc}}</td>
                            <td>{{$topup->bank}}</td>
                            <td>{{$topup->amount}}</td>
                            <td>{{$topup->txnid}}</td>
                            <td>{{$topup->rrn}}</td>
                            <td>{{$topup->transaction_date}}</td>
                            <td>
                                @if($topup->status == 'PENDING')
                                    <button class="btn btn-success approve" data-id = "{{$topup->id}}">Approve</button>
                                    <button class="btn btn-danger reject" data-id = "{{$topup->id}}">Reject</button>
                                @elseif($topup->status == 'REJECTED')
                                    <button class="btn btn-danger" data-id = "{{$topup->id}}">Rejected</button>
                                @else
                                    <button class="btn btn-success">Approved</button>
                                @endif
                                <button class="btn btn-info view-proof" data-proof = "{{$topup->proof}}">View Proof</button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
<script>
    // $(".view-proof").click(function()
    $(document).on("click",'.view-proof', function()
    {
        var $button = $(this);
        var url = $button.data('proof');
        window.open(url,'_blank');
    })
    // $(".approve").click(function()
    $(document).on("click",'.approve', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        var id = $button.data('id');
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/topup-request-action/approve/'+id,
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
    // $(".reject").click(function()
    $(document).on("click",'.reject', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        var id = $button.data('id');
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/topup-request-action/reject/'+id,
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