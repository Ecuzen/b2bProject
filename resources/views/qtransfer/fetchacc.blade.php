<div class="form-validation">
    <form class="needs-validation" novalidate >
        <div class="row">
            <div class="col-xl-12">
                @foreach($result as $result)
                        <div class="col-lg-12 text-dark" id="fundbank">
                            <div class="card bg-success">
                               <div class="row g-4">
                                   <div class="col-lg-2">Account:-</div>
                                   <div class="col-lg-4" data-account='{{$result->account}}'>{{$result->account}}</div>
                                   <div class="col-lg-2">IFSC:-</div>
                                   <div class="col-lg-4" data-ifsc="{{$result->ifsc}}">{{$result->ifsc}}</div>
                               </div>
                               <div class="row g-4">
                                   <div class="col-lg-2">NAME:-</div>
                                   @if($result->fetch_acc == 1)
                                   <div class="col-lg-4 checkverify" value="{{$result->bname}}"  data-name="{{$result->bname}}">{{$result->bname}}</div>
                                   <div class="col-lg-3"><button class="btn btn-primary font-medium rounded-pill px-4" type="button"><i class="ti ti-select me-2 fs-4"></i>Verify</button></div>
                                   @else
                                   <div class="col-lg-4 checkverify" value="{{$result->bname}}"  data-name="{{$result->bname}}">{{$result->bname}}</div>
                                   <div class="col-lg-3"><button class="btn btn-primary font-medium rounded-pill px-4" type="button"><i class="ti ti-select me-2 fs-4"></i>Not Verify</button></div>
                                   @endif
                                   <div class="col-lg-3"><button class="btn btn-primary font-medium rounded-pill px-4" type="button" data-id="{{$result->id}}"><i class="ti ti-select me-2 fs-4"></i>Select</button></div>
                               </div>
                            </div>
                        </div>
                        @endforeach                          
            </div>                      
        </div>
    </form>
</div>
<script>
    $("#fundbank button").click(function() {
        $button  = $(this);
          var account = $(this).closest(".card").find("[data-account]").data("account");
          var ifsc = $(this).closest(".card").find("[data-ifsc]").data("ifsc");
          var name = $(this).closest(".card").find("[data-name]").data("name");
          $("#account").val(account);
          $("#ifsc").val(ifsc);
          $("#name").val(name);
          var data = $button.closest('.row').find('.checkverify').text();
          var len = data.length;
          if(len > 0)
          {
               $('.verify').prop('disabled', true);
          }else{
              $('.verify').prop('disabled', false);
          }
          $(`#bankselect option[value='${ifsc}']`).prop('selected', true);
        });
        
</script>