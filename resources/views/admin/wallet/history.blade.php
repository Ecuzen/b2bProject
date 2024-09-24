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
                        <th>Type</th>
                        <th>Txn Type</th>
                        <th>Remark</th>
                        <th>Txn Id</th>
                        <th>Amount</th>
                        <th>Opening</th>
                        <th>Closing</th>
                        <th>Time stamp</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($walletHistory as $history)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$history->name}}</td>
                            <td>{{$history->username}}</td>
                            <td>{{$history->phone}}</td>
                            <td>{{$history->type}}</td>
                            <td>{{$history->txntype}}</td>
                            <td>{{$history->remark}}</td>
                            <td>{{$history->txnid}}</td>
                            <td>{{$history->amount}}</td>
                            <td>{{$history->opening}}</td>
                            <td>{{$history->closing}}</td>
                            <td>{{$history->date}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
@include('admin.footer')