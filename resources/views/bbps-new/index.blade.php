@include('users.header')
<style>
   
    .icon-img img{
        width: 100%;
        
    }
            .dash-service{
            min-height: 180px !important;
        }
</style>

<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Pay Bills and Recharge</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">BBPS</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/dmtlogo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          
          
          
          <div id="content">
            <div class="card-body p-4 pb-0">
                <div class="row">
                    @foreach($results as $result)
                        <div class="col-6 col-lg-2 get-category" data-key="{{$result->cat_key}}">
                            <div class="card dash-service hover-img overflow-hidden rounded-2 justify-content-center">
                              <div class="position-relative bbps-icons">
                                <a href="{{route('bbps.fetch_by_cat',[$result->cat_key])}}"><img src="{{$result->icon}}" class="card-img-top rounded-0" alt="{{$result->name}}"></a>
                             </div>
                              <div class="pt-3 p-1 text-center">
                                <h6 class="fw-semibold fs-4 font-weight-bold">{{$result->name}}</h6>
                              </div>
                            </div>
                        </div>
                    @endforeach
                </div>              
              </div>
         </div>
</div>
@include('users.footer')