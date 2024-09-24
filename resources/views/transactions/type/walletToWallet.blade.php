<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive table-txn">
                      <table id="file_export" class="table border table-striped table-bordered display text-nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>SENDER NAME</th>
                            <th>SENDER MOBILE</th>
                            <th>RECEIVER NAME</th>
                            <th>RECEIVER MOBILE</th>
                            <th>TXNID</th>
                            <th>TYPE</th>
                            <th>AMOUNT</th>
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
                                <td>{{$txn['senderName']}}</td>
                                <td>{{$txn['senderPhone']}}</td>
                                <td>{{$txn['receiverName']}}</td>
                                <td>{{$txn['receiverphone']}}</td>
                                <td>{{$txn['txnid']}}</td>
                                <td>{{$txn['type']}}</td>
                                <td>{{$txn['amount']}}</td>
                                <td>{{$txn['date']}}</td>
                                 <td><button class="btn btn-outline-info w-10 transactions-receipt" data-transactionId = "{{$txn->txnid}}">Receipt</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>#</th>
                            <th>SENDER NAME</th>
                            <th>SENDER MOBILE</th>
                            <th>RECEIVER NAME</th>
                            <th>RECEIVER MOBILE</th>
                            <th>TXNID</th>
                            <th>TYPE</th>
                            <th>AMOUNT</th>
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
<script src="{{url('assets')}}/dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="{{url('assets')}}/cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="{{url('assets')}}/cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
    <script src="{{url('assets')}}/dist/js/datatable/datatable-advanced.init.js"></script>