<style>
    .smaller-image {
  max-width: 150px; /* Adjust the desired maximum width */
  max-height: 120px; /* Adjust the desired maximum height */
}
.scrollable-row {
    overflow-x: auto;
    white-space: nowrap;
    max-width: 100%;
  }

</style>


<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="mb-2"></div>
            <center>
                <img src="{{$logo}}" alt="" class="img-fluid smaller-image"/>
            </center>
            
           <input type="hidden" value="{{$fKey}}" id="fKey">
                    <!-- Nav tabs -->
            <div class="scrollable-row overflow-x-scroll">
                <ul class="nav nav-pills nav-fill mt-4 mb-2" style="flex-wrap : inherit" role="tablist">
                    @forelse($plans as $key =>$plan)
                    <li class="nav-item me-2">
                      <button class="nav-link active get-plan data-button" id="plan-{{$key}}" data-bs-toggle="tab" href="#navpill-111" role="tab" data-id="{{$key}}">
                       {{$plan->group_name}}
                      </button>
                    </li>
                    
                    @empty 
                    <h1>Plan Not exist</h1>
                    @endforelse
                </ul>
            </div>
            
            <!--search-->
            <div class="container-fluid mt-2 mb-3">
                <div class="row height d-flex justify-content-center align-items-center">
                    <div class="col-md-12">
                            <div class="search">
                                <input id="search" type="text" class="form-control" placeholder="Search Recharge Price" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
                            </div>
                    </div>
                </div>
            </div>
            
            <!--search end-->
            <div class="row row-class">
                @foreach($plans as $key => $plan)
                <div class="col-md-12 inn-class" id="{{$key}}" data-id="{{$key}}">
                    
                    
                        @forelse($plan->plans as $pkey => $pdata)
                        
                        
                             <div class="card inner" data-damount='{{$pdata->price ?? 0}}'>
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-md-8"></div>
                                          <div class="col-md-4">
                                              <button id="damount" class="btn btn-danger numeric-input" data-amount="{{$pdata->price ?? 0}}">₹ {{$pdata->price ?? 0}}</button>
                                          </div>
                                      </div>
                                    <p><b>Validity:</b></b> {{$pdata->validity ?? ''}}</p>
                                    <p><b>Details:</b> {{urldecode($pdata->desc ?? '')}}</p>
                                  </div>
                            </div>
                        @empty
                        
                            <div class="card inner" data-damount='0'>
                                  <div class="card-body">
                                    <p>Data not exist</p>
                                  </div>
                            </div>
                        @endforelse
            
                        
                    
                </div>
                @endforeach
            </div>
            
            
        </div>
    </div>
</div>
              
<div id="plan_details"></div>
         
     
        
<script>
    $(document).ready(function() {
    
    // showplan on click 
      $(".data-button").click(function() {
        var id = $(this).data('id');
        var prevView =  $('#' + id);
        prevView.prependTo('.row-class');
      });
      
      
     
    // search filteration for recharge amount
      $('#search').keyup(function(event) {
            var searchTerm = $(this).val();
            
            $('.inner').each(function() {
                var userAmount = $(this).data('damount').toString();
                
                $(this).filter(function(index) {
                      if (userAmount.includes(searchTerm)) {
                            $(this).show();
                      } else {
                            $(this).hide();
                      }
                });
        
                
            });
        });
    $('.inner').click(function(){
       var amt =  $(this).data('damount');
       var amount = $('#amount').val(amt);
        if(amount != ''){
            $(".recharge").show();
            $(".fetch-plans").show();
        }else{
            $(".recharge").hide();
            $(".fetch-plans").hide();
        }
        $('#plansModal').modal('hide');
        
    })
        
    
    
        
    });
    
    
</script>



 
 
 
 
 

 
 
 
 
 
 
 
 
 
 
 
 
 
 