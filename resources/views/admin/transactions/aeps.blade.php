<link rel="stylesheet" href="{{url('assets')}}/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
    @php
        $pending = (float)0;
        $success = (float)0;
        $failed = (float)0;
        
        foreach($txnData as $txn1)
        {
            if($txn1->status == "PENDING")
                $pending += (float)$txn1->amount;
            elseif($txn1->status == "SUCCESS")
                $success += (float)$txn1->amount;
            elseif($txn1->status == "FAILED" || $txn1->status == "ERROR")
                $failed += (float)$txn1->amount;
        }
    @endphp
    <div class="row">
        <div class="col-2"><strong>SUCCESS:-</strong>{{ $success }}</div>
        <div class="col-2"><strong>FAILED:-</strong>{{ $failed }}</div>
        <div class="col-2"><strong>PENDING:-</strong>{{ $pending }}</div>
    </div>
<div class="col-12">
<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
          <tr>
            <th>#</th>
            <th>USERNAME</th>
            <th>TYPE</th>
            <th>TXNID</th>
            <th>AMOUNT</th>
            <th>STATUS</th>
            <th>MESSAGE</th>
            <th>AADHAR</th>
            <th>RRN</th>
            <th>BANK</th>
            <th>DATE</th>
             <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
            @php
            $i = 0;
            @endphp
            @foreach($txnData as $txn)
            <tr>
                <td>{{++$i}}</td>
                <td>{{$txn->username}}</td>
                <td>{{$txn->type}}</td>
                <td>{{$txn->txnid}}</td>
                <td>{{$txn->amount}}</td>
                <td>{{$txn->status}}</td>
                <td>{{$txn->message}}</td>
                <td>{{$txn->aadhar}}</td>
                <td>{{$txn->rrn}}</td>
                <td>{{$txn->bank_name}}</td>
                <td>{{$txn->date}}</td>
                 <td><button class="btn btn-outline-info w-10 transactions-receipt" onclick="transactionsreceipt('{{$txn->txnid}}', $(this))" data-transactionId = "{{$txn->txnid}}">Receipt</button></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th>#</th>
            <th>USERNAME</th>
            <th>TYPE</th>
            <th>TXNID</th>
            <th>AMOUNT</th>
            <th>STATUS</th>
            <th>MESSAGE</th>
            <th>AADHAR</th>
            <th>RRN</th>
            <th>BANK</th>
            <th>DATE</th>
             <th>ACTION</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
    </div>
  </div>
</div>
    
    
    
        <!--Datatables-->
        <script src="{{url('assets')}}/dashboard/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/dataTables.bootstrap5.min.js"></script>
        <!-- Buttons examples -->
        <script src="{{url('assets')}}/dashboard/plugins/datatables/dataTables.buttons.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.bootstrap5.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/jszip.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/pdfmake.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/vfs_fonts.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.html5.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.print.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/buttons.colVis.min.js"></script>
        <!-- Responsive examples -->
        <script src="{{url('assets')}}/dashboard/plugins/datatables/dataTables.responsive.min.js"></script>
        <script src="{{url('assets')}}/dashboard/plugins/datatables/responsive.bootstrap4.min.js"></script>
        <script src="{{url('assets')}}/dashboard/assets/pages/jquery.datatable.init.js"></script>
        
        
        <script>
               function transactionsreceipt(txnid, button){
            if(txnid.length <= 4)
            {
                info('No transactions found');
                return;
            }
            var previous = button.html();
            button.html("Loading!!");
            button.prop('disabled',true);
            $.ajax({
                url : '/transactions-receipt-details/aeps/'+txnid,
                success:function(data)
                {
                    button.html(previous);
                    button.prop('disabled',false);
                    if(data.status == 'SUCCESS')
                    {
                        $("#transactions-receipt-modal").modal("show");
                        $("#receipt-data").html(data.view);
                    }
                    else
                    info(data.message);
                }
            })
        }
        </script>