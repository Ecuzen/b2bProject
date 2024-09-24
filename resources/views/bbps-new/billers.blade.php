@include('users.header')
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
                        <img src="{{url('assets')}}/images/dmtlogo.png" alt="" class="img-fluid mb-n4" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="content">
        <div class="card-body p-4 pb-0">
            <div class="row">
                <div class="col-lg-6">
                    <!-- ---------------------
                                                            start Success Border with Icons
                                                        ---------------- -->
                    <div class="card">
                        <div class="card-body">
                            <h5>{{$catName}}</h5>

                            <div class="mb-3">
                                <select class="form-control border border-ecuzen select-biller">
                                    <option value="0">select</option>
                                    @foreach($data as $dat)
                                    <option data-thumbnail="{{$dat->icon_url}}" value="{{$dat->id}}">{{$dat->biller_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="biller-section">
                                <a href="javascript:void(0)" class="px-4 py-3 bg-hover-light-black d-flex align-items-center chat-user" id="chat_user_4" data-user-id="4">
                                <span class="position-relative">
                                  <img  alt="user8" width="40" height="40" class="rounded-circle biller-image">
                                </span>
                                <div class="ms-6 d-inline-block w-75">
                                  <h6 class="mb-1 fw-semibold biller-title"></h6>
                                </div>
                              </a>
                            </div>
                            <div id="paramContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-primary back-button"><i class="ti ti-arrow-back w-100"></i> Back</button>
</div>
<script>
    $(document).ready(function(){
        $(".biller-section").hide();
        $(".back-button").click(function(){
            window.history.back();
        })
        $(".select-biller").on('change',function(){
            var billerId = $(".select-biller").val();
            if(billerId == 0)
            {
                $(".biller-section").hide();
                return;
            }
            const selectedOption = $(this).find(':selected');
            const thumbnailUrl = selectedOption.data('thumbnail');
            $(".biller-section").show();
            $(".biller-image").attr('src',thumbnailUrl);
            $(".biller-title").html(selectedOption.html());
            disableBody();
            $.ajax({
                url: "{{ route('bbps.fetch_biller.params', ['billerId' => ':id']) }}".replace(':id', billerId),
                success:function(data)
                {
                    enableBody();
                    $("#paramContent").html('');
                    if(data.status == 'SUCCESS')
                    {
                        $("#paramContent").html(data.view);
                    }
                    else
                    info(data.message);
                }
            })
        })
    })
</script>
@include('users.footer')
