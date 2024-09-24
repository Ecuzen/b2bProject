<style>
.scrollable-row {
    overflow-x: auto;
    white-space: nowrap;
    max-width: 100%;
  }

</style>

<!--search-->
            <div class="container-fluid">
                <div class="row height d-flex justify-content-center align-items-center">
                    <div class="col-md-12">
                        <div class="search">
                            <!--<i class="fa fa-search"></i>-->
                            <input id="search" type="text" class="form-control" placeholder="Search Recharge Price">
                        </div>
                    </div>
                </div>
            </div>
<!--search end-->

<div id="searchDiv"></div>

@if(isset($keyData))
    @forelse($keyData as $keydt)
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
              <div class="row">
                  <div class="col-md-8"></div>
                  <div class="col-md-4">
                      <button class="btn btn-danger">₹ {{$keydt->rs}}</button>
                  </div>
              </div>
            <p><b>Validity:</b></b> {{$keydt->validity}}</p>
            <p><b>Details:</b> {{urldecode($keydt->desc)}}</p>
          </div>
        </div>
    <div>
    @empty
        <h3>No Data Found<h3>
    @endforelse
@endif




<script>
$("#search").keypress(function(){
    alert('searching');
    var searchval = $('#search').val();
    console.log(searchval);
    
    if(searchval.length >= 2){
        $.ajax({
            url : '/search-plan',
            method : 'GET',
            data : {
                "searchval" : searchval,
            },
            success: function(data) {
                console.log(data);
                $('#searchDiv').html(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
    })  
    }else{
        $('#searchDiv').html('');
    }
    
    
});
</script>