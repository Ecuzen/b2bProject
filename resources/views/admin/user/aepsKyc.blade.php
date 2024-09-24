@include('admin.header')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">  
                <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Aadhar</th>
                        <th>PAN</th>
                        <th>OUTLET ID</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
    
                    @php
                    $i=0;
                    @endphp
                    <tbody>
                        @foreach($aepsKyc as $kyc)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{$kyc->user_name}}</td>
                            <td>{{$kyc->phone}}</td>
                            <td>{{$kyc->adhaar}}</td>
                            <td>{{$kyc->pan}}</td>
                            <td>{{$kyc->outlet}}</td>
                            @if($kyc->kyc_status == 1)
                            <td>OTP verification Not complete</td>
                            @elseif($kyc->kyc_status == 2)
                            <td>Biomatric verification Not complete</td>
                            @else
                            <td>AEPS KYC completed</td>
                            @endif
                            <td>
                                <button class="btn btn-danger aeps-kyc-delete" data-id = "{{$kyc->id}}">Delete</button> <button class="btn btn-info kyc-details" data-id = "{{$kyc->id}}">View Details</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>        
            </div>
        </div>
    </div> 
</div>
<div class="modal fade" id="backDropmodal" data-bs-backdrop="static"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title m-0" id="backDropmodalTitle">User Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div><!--end modal-header-->
            <div class="modal-body backDropmodal-body">
                <div class="mb-3 row">
                    <!--Name-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Name</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-name font-weight-bold"></label>
                    <!--Father Name-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Father Name</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-father font-weight-bold"></label>
                    <!--Date of Birth-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">DOB</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-dob font-weight-bold"></label>
                    <!--Phone-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Phone</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-phone font-weight-bold"></label>
                    <!--Aadhar-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Aadhar</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-aadhar font-weight-bold"></label>
                    <!--Pan-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">PAN</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-pan font-weight-bold"></label>
                    <!--address-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Address</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-address font-weight-bold"></label>
                    <!--district-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">District</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-district font-weight-bold"></label>
                    <!--state-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">State</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-state font-weight-bold"></label>
                    <!--pincode-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Pin Code</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start user-pin font-weight-bold"></label>
                    <!--shopname-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Shop Name</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start shop-name font-weight-bold"></label>
                    <!--shop address-->
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start">Shop Address</label>
                    <label class="col-sm-6 form-label align-self-center mb-lg-0 text-start shop-address font-weight-bold"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-secondary btn-sm text-warning" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    var kycData = @json($aepsKyc);
    // $(".aeps-kyc-delete").click(function()
     $(document).on("click",'.aeps-kyc-delete', function()
    {
        var $button = $(this);
        var originalText = $button.text();
        $button.html("Waiting!!!");
        $button.prop("disabled", true);
        $.ajax({
            url : '/aeps-kyc-delete/'+$button.data('id'),
            success:function(data)
            {
                if(data.status == 'SUCCESS')
                {
                    successReload(data.message);
                }
                else
                {
                    error(data.message);
                }
            }
        })
    })
    // $(".kyc-details").click(function()
     $(document).on("click",'.kyc-details', function()
    {
        var $button = $(this);
        var id = $button.data('id');
        $.each(kycData, function (index, kycData) {
            if(id == kycData.id)
            {
                $(".user-name").html(kycData.user_name);
                $(".user-father").html(kycData.fname);
                $(".user-dob").html(kycData.dob);
                $(".user-phone").html(kycData.phone);
                $(".user-aadhar").html(kycData.adhaar);
                $(".user-pan").html(kycData.pan);
                $(".user-address").html(kycData.address);
                $(".user-district").html(kycData.district);
                $(".user-state").html(kycData.stateName);
                $(".user-pin").html(kycData.pincode);
                $(".shop-name").html(kycData.shopname);
                $(".shop-address").html(kycData.shopaddress);
            }
        });
    $("#backDropmodal").modal("show");
    })
</script>
@include('admin.footer')