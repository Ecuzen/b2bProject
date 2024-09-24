@include('users.header')
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">PAN Verification</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted" href="{{route('home')}}">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">PAN Verification</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{url('assets')}}/images/pan-verification.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="content">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5>PAN Number</h5>
                        <form class="pan-veriction-form">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control border border-ecuzen text-uppercase" placeholder="Enter your PAN Number" name="pan" maxlength="10" />
                                <label><i class="ti ti-id-badge-2 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3"></span>Enter your PAN Number</label>
                            </div>

                            <div class="d-md-flex align-items-center">
                                <div class="mt-3 mt-md-0 mx-auto">
                                    <button type="submit" class="btn btn-blue font-medium rounded-pill px-4 dmt-login">
                                        <div class="d-flex align-items-center text-white">
                                            <i class="ti ti-send me-2 fs-4"></i>
                                            Verify
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 pan-data"></div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $(".pan-veriction-form").on('submit',function(e){
            e.preventDefault();
            var formdata = new FormData(this);
            formdata.set('pan', formdata.get('pan').toUpperCase());
            formdata.append('_token',"{{csrf_token()}}");
            formdata.append('lat',$("#latitude").val());
            formdata.append('log',$("#longitude").val());
            disableBody();
            $.ajax({
                url : "{{route('verifyPan')}}",
                method : 'post',
                data : formdata,
                contentType : false,
                processData: false,
                success:function(data)
                {
                    enableBody();
                    if(data.status == 'SUCCESS')
                    $(".pan-data").html(data.view);
                    else
                    error(data.message);
                }
            })
        })
    })
</script>
@include('users.footer')
