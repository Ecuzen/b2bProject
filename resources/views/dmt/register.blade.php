<div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <div class="card">
                 <div class="card-body">
                    <h5>Money Transfer <b><i>User Registration</i></b></h5>
                    <form class="">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen " placeholder="First Name" id="fname"/>
                            <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">First Name</span></label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen " placeholder="Last Name" id="lname"/>
                            <label><i class="ti ti-user me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Last Name</span></label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="OTP"  id="otp"/>
                            <label><i class="ti ti-123 me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">OTP</span></label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen numeric-input" placeholder="Pincode"  id="pincode"/>
                            <label><i class="ti ti-map-pin-code me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3">Pincode</span></label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border border-ecuzen" placeholder="Resident Address" id="address"/>
                            <label><i class="ti ti-map-pin me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3"></span>Resident Address</label>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="date" class="form-control border border-ecuzen " placeholder="Date of Birth" id="dob"/>
                            <label><i class="ti ti-calendar-due me-2 fs-4 text-eczn-blue"></i><span class="border-start line-eczn ps-3"></span>Date of Birth</label>
                        </div>
                        <div class="d-md-flex align-items-center">
                        <div class="mt-3 mt-md-0 mx-auto">
                          <button type="button" class="btn btn-blue font-medium rounded-pill px-4 dmt-register-submit" >
                            <div class="d-flex align-items-center text-white"><i class="ti ti-send me-2 fs-4"></i>Submit</div>
                          </button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
          </div>
          <input type="hidden" value="{{$mobile}}" id="mobile">
          <input type="hidden" value="{{$key}}" id="key">
<script>
    var currentDate = new Date();
    var maxDate = new Date(currentDate);
    maxDate.setMonth(currentDate.getMonth() );
    var maxDateString = maxDate.toISOString().slice(0, 10);
    $("input[type='date']").attr('max', maxDateString);
</script>