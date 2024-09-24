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
<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="file_export" class="table border table-striped table-bordered display text-nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>USERNAME</th>
                            <th>TXNID</th>
                            <th>ACCOUNT</th>
                            <th>IFSC</th>
                            <th>NAME</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>MODE</th>
                            <th>MESSAGE</th>
                            <th>RRN</th>
                            <th>DATE</th>
                            <th>Check Status</th>
                          </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach($txnData as $txn)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$txn->txnid}}</td>
                                <td>{{$txn->username}}</td>
                                <td>{{$txn->account}}</td>
                                <td>{{$txn->ifsc}}</td>
                                <td>{{$txn->bname}}</td>
                                <td>{{$txn->amount}}</td>
                                <td>{{$txn->status}}</td>
                                <td>{{$txn->mode}}</td>
                                <td>{{$txn->message}}</td>
                                <td>{{$txn->rrn}}</td>
                                <td>{{$txn->date}}</td>
                                <td><button class="btn btn-danger checkstatus" data-txnid="{{$txn->txnid}}">Check Status</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>#</th>
                            <th>USERNAME</th>
                            <th>TXNID</th>
                            <th>ACCOUNT</th>
                            <th>IFSC</th>
                            <th>NAME</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>MODE</th>
                            <th>MESSAGE</th>
                            <th>RRN</th>
                            <th>DATE</th>
                            <th>Check Status</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
    <script src="{{url('assets')}}/dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="{{url('assets')}}/dist/js/datatable/datatable-advanced.init.js"></script>