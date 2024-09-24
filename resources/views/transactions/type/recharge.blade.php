<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive table-txn">
                      <table id="file_export" class="table border table-striped table-bordered display text-nowrap">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>TXNID</th>
                            <th>MOBILE</th>
                            <th>OPERATOR</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
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
                                <td>{{$txn->txnid}}</td>
                                <td>{{$txn->mobile}}</td>
                                <td>{{$txn->operator}}</td>
                                <td>{{$txn->amount}}</td>
                                <td>{{$txn->status}}</td>
                                <td>{{$txn->date}}</td>
                                 <td><button class="btn btn-outline-info w-10 transactions-receipt" data-transactionId = "{{$txn->txnid}}">Receipt</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>#</th>
                            <th>TXNID</th>
                            <th>MOBILE</th>
                            <th>OPERATOR</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
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