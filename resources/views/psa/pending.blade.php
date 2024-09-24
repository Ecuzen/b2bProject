@include('users.header')
<style>
 .application-pending {
  text-align: center;
  padding: 20px;
}

.logo-container {
  margin-bottom: 20px;
  animation: logo-pulse 1.5s infinite;
}

.logo {
  max-width: 200px;
}

.title {
  font-size: 24px;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.description {
  font-size: 16px;
  color: #666;
}

@keyframes logo-pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    transform: scale(1);
  }
}




</style>
<div class="container-fluid">
          <div class="card bg-light-info shadow-none position-relative overflow-hidden">
            <div class="card-body px-4 py-3">
              <div class="row align-items-center">
                <div class="col-9">
                  <h4 class="fw-semibold mb-8">PSA</h4>
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item"><a class="text-muted" href="/">Dashboard</a></li>
                      <li class="breadcrumb-item" aria-current="page">PSA</li>
                    </ol>
                  </nav>
                </div>
                <div class="col-3">
                  <div class="text-center mb-n5">  
                    <img src="{{url('assets')}}/images/psa-logo.png" alt="" class="img-fluid mb-n4">
                  </div>
                </div>
              </div>
            </div>
          </div>
        <div class="application-pending">
          <div class="logo-container">
            <img src="{{$logo}}" alt="Company Logo" class="logo">
          </div>
          <h2 class="title">Application Pending</h2>
          <p class="description">Your application is currently being processed. Please wait for further updates.</p>
        </div>


</div>
@include('users.footer')