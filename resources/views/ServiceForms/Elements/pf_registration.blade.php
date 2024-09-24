<form enctype="multipart/form-data" class="row g-3" method="POST" action="{{ route('service_form.store') }}">
    @csrf
    <input type="hidden" name="service_id" value="{{$service_id}}">
    <h6 class="mb-0 text-uppercase">Firm Details</h6>
    <div class="col-md-6">
        <label for="firm_name" class="form-label">Firm name<sup style="color:red;">*</sup></label>
        <div class="input-group">
            <input value="{{old('name')}}" type="text" name="firm_name" class="form-control border-start-0" id="firm_name" placeholder="Enter firm name" required>
        </div>
        @error('firm_name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Document Details</h6>

    <div class="row">
        <div class="col-md-6">
            <label for="pan_card_number" class="form-label">Pan number<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text" name="pan_card_number" class="form-control border-start-0" id="pan_card_number" placeholder="Enter pan number" required>
            </div>
            @error('pan_card_number')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="pan_card_file_web" class="form-label">Pan card file</label>
            <div class="input-group">
                <input type="hidden" name="pan_card_file" id="pan_card_file">
                <input type="file" class="form-control border-start-0" id="pan_card_file_web" accept="image/*" data-file-type="Image" required>
            </div>
            @error('pan_card_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="aadhar_number" class="form-label">Aadhar card number<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text" name="aadhar_number" class="form-control border-start-0" id="aadhar_number" placeholder="Enter aadhar number" required>
            </div>
            @error('aadhar_number')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="front_side_aadhar_file_web" class="form-label">Aadhar card front file</label>
            <div class="input-group">
                <input type="hidden" name="front_side_aadhar_file" id="front_side_aadhar_file">
                <input type="file" class="form-control border-start-0" id="front_side_aadhar_file_web" accept="image/*" data-file-type="Image" required>
            </div>
            @error('front_side_aadhar_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="back_side_aadhar_file_web" class="form-label">Aadhar card back file</label>
            <div class="input-group">
                <input type="hidden" name="back_side_aadhar_file" id="back_side_aadhar_file">
                <input type="file" class="form-control border-start-0" id="back_side_aadhar_file_web" accept="image/*" data-file-type="Image" required>
            </div>
            @error('back_side_aadhar_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="user_profile_file_web" class="form-label">Self Photo</label>
            <div class="input-group">
                <input type="hidden" name="user_profile_file" id="user_profile_file">
                <input type="file" class="form-control border-start-0" id="user_profile_file_web" accept="image/*" data-file-type="Image" required>
            </div>
            @error('user_profile_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="itr_mobile" class="form-label">Mobile Number<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text" name="itr_mobile" class="form-control border-start-0" id="itr_mobile" placeholder="Enter mobile number" required>
            </div>
            @error('itr_mobile')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="alternate_phone" class="form-label">Alternative Mobile Number</label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text" name="alternate_phone" class="form-control border-start-0" id="alternate_phone" placeholder="Enter alternative mobile number">
            </div>
            @error('alternate_phone')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="itr_email" class="form-label">Email id <sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="email" name="itr_email" class="form-control border-start-0" id="itr_email" placeholder="Enter email id" required>
            </div>
            @error('itr_email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Gst Registration Certificate</h6>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="gst_certificate" class="form-label">Gst Registration Number<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text" name="gst_certificate" class="form-control border-start-0" id="gst_certificate" placeholder="Enter gst registration number" required>
            </div>
            @error('gst_certificate')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="employee_number" class="form-label">Number of employee<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="number" name="employee_number" class="form-control border-start-0" id="employee_number" placeholder="Enter number of employee" required>
            </div>
            @error('employee_number')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="gst_certificate_file_web" class="form-label">Gst Registration file</label>
            <div class="input-group">
                <input type="hidden" name="gst_certificate_file" id="gst_certificate_file">
                <input type="file"  class="form-control border-start-0" id="gst_certificate_file_web" accept="image/*" data-file-type="Image" required>
            </div>
            @error('gst_certificate_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="self_declaration_form_first_web" class="form-label">Attachment - 1</label>
            <div class="input-group">
                <input type="hidden" name="self_declaration_form_first" id="self_declaration_form_first">
                <input type="file" class="form-control border-start-0" accept="image/*" data-file-type="Image" id="self_declaration_form_first_web">
            </div>
            @error('self_declaration_form_first')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="self_declaration_form_second_web" class="form-label">Attachment - 2</label>
            <div class="input-group">
                <input type="hidden" name="self_declaration_form_second" id="self_declaration_form_second">
                <input type="file" class="form-control border-start-0" accept="image/*" data-file-type="Image" id="self_declaration_form_second_web">
            </div>
            @error('self_declaration_form_second')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-danger px-5">Submit</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('input[type="file"]').on('change', function()
        {
            var fileInput = $(this);
            var inputId = fileInput.attr('id');
            var formData = new FormData();
            console.log(inputId + " __inputId");
            hidden_file_input_id = inputId.replace("_web", "");
            console.log(hidden_file_input_id + " __hidden_file_input_id");
            formData.append('file_name', fileInput[0].files[0]);
            formData.append('file_type',fileInput.attr('data-file-type'));
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                url : '{{ route("web.file_uploads") }}',
                type : 'POST',
                data : formData,
                processData : false,
                contentType : false,
                beforeSend : function()
                {
                    $("#loader_login").show();
                },
                success : function(response)
                {
                    $("#loader_login").hide();
                    if(response.status_code == 200)
                    {
                        $('#'+hidden_file_input_id).val(response.data.file_name);
                    }
                },
                error: function(xhr)
                {
                    $("#loader_login").hide();
                    alert('File upload failed!');
                    // Handle the error
                }
            });
        });
    });
</script>
