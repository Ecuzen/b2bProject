@include('admin.header')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">  
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Type</th>
                        <th>IP</th>
                        <th>Lat</th>
                        <th>Log</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$log->username}}</td>
                            <td>{{$log->type}}</td>
                            <td>{{$log->ip}}</td>
                            <td>{{$log->lat}}</td>
                            <td>{{$log->log}}</td>
                            <td><button class="btn btn-warning view-location" data-lat="{{$log->lat}}" data-log="{{$log->log}}">View Location</button> </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
<script>
    // $(".view-location").click(function()
    $(document).on("click",'.view-location', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        var lat = $button.data('lat');
        var log = $button.data('log');
        const url = "https://www.google.com/maps?q="+lat+','+log;
        window.open(url, "_blank");
        $button.html(originalText);
        $button.prop("disabled", false);
    })
</script>
@include('admin.footer')