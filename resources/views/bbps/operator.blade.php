<div class="row">
                  <div class="col-lg-3"></div>
                  <div class="col-lg-6">
                        <!-- ---------------------
                                                            start Success Border with Icons
                                                        ---------------- -->
                        <div class="card">
                          <div class="card-body">
                            <h5>{{$catName}}</h5>
                            <form class="">
                              <div class=" mb-3">
                                <select class="form-control border border-ecuzen" id="biller">
                                    <option>select</option>
                                    @foreach($data as $dat)
                                    <option value="{{$dat->id}}">{{$dat->name}}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div id="paramContent">
                                  
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
            </div>
<script>
    $( document ).ready(function(){
        $("#biller").on("change", function() {
        $.ajax({
            url : 'get-biller-params',
            method : 'get',
            data : {
                'billerId' : $(this).val()
            },
            success:function(data)
            {
                $("#paramContent").html(data.view);
            }
        })
        })
    })
</script>