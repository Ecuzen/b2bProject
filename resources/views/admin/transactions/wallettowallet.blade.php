<div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>SENDER USERNAME</th>
                            <th>SENDER NAME</th>
                            <th>SENDER PHONE</th>
                            <th>RECEIVER USERNAME</th>
                            <th>RECEIVER NAME</th>
                            <th>RECEIVER PHONE</th>
                            <th>TYPE</th>
                            <th>TXNID</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
                          </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach($txnData as $txn)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$txn['senderUsername']}}</td>
                                <td>{{$txn['senderName']}}</td>
                                <td>{{$txn['senderPhone']}}</td>
                                <td>{{$txn['receiverUsername']}}</td>
                                <td>{{$txn['receiverName']}}</td>
                                <td>{{$txn['receiverphone']}}</td>
                                <td>{{$txn['type']}}</td>
                                <td>{{$txn['txnid']}}</td>
                                <td>{{$txn['amount']}}</td>
                                <td>{{$txn['date']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>#</th>
                            <th>SENDER USERNAME</th>
                            <th>SENDER NAME</th>
                            <th>SENDER PHONE</th>
                            <th>RECEIVER USERNAME</th>
                            <th>RECEIVER NAME</th>
                            <th>RECEIVER PHONE</th>
                            <th>TYPE</th>
                            <th>TXNID</th>
                            <th>AMOUNT</th>
                            <th>DATE</th>
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