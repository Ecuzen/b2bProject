@include('users.header')
<style>
    .no-scrollbar::-webkit-scrollbar {
    display: none;
  }
</style>
<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">Other services</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" >service</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/recharge-mobile.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
             <div class="col-lg-12">
              <div class="card">
                   <div class="card-header">
                        <h4 class="card-title" id="card-title">Other Services</h4>
                    </div>
                <div class="card-body p-4" id="txndata">
                    <div class="table-responsive rounded-2 mb-4 overflow-scroll no-scrollbar">
                <table class="table border text-nowrap customize-table mb-0 align-middle ">
                  <thead class="text-dark fs-4">
                    <tr>
                        <th><h6 class="fs-4 fw-semibold mb-0">#</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Name</h6></th>
                        <th><h6 class="fs-4 fw-semibold mb-0">Link</h6></th>
                    </tr>
                  </thead>
                    <tbody>
                        @php
                        $i=0;
                        @endphp
                      @foreach($otherservices as $service)
                    <tr>
                        <td><h6 class="fw-semibold mb-0">{{++$i}}</h6></td>
                      <td><h6 class="fw-semibold mb-0">{{$service->name}}</h6></td>
                      <td>
                          <a href="{{$service->link}}" target="_blank"><button type="button" class="btn btn-outline-success w-100 raise-ticket">Link</button></a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
                </div>
              </div> 
            </div>
          </div> 
</div>
@include('users.footer')