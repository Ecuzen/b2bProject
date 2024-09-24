<form enctype="multipart/form-data" class="row g-3" method="POST" action="{{ route('service_form.store') }}">
    @csrf
    <input type="hidden" name="service_id" value="{{$service_id}}">
    <h6 class="mb-0 text-uppercase">Firm Details<sup style="color:red;">*</sup></h6>
    <div class="col-md-6">
        <label for="firm_name" class="form-label">Firm name</label>
        <div class="input-group">
            <input value="{{old('name')}}" type="text" name="firm_name" class="form-control border-start-0" id="firm_name" placeholder="Enter firm name" required>
        </div>
        @error('firm_name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="firm_description" class="form-label">Firm description<sup style="color:red;">*</sup></label>
        <div class="input-group">
            <textarea name="firm_description" class="form-control border-start-0" id="firm_description" placeholder="Enter firm description" required></textarea>
        </div>
        @error('firm_description')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Document Details</h6>

    <div class="row">
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
            <label for="pan_card_number_web" class="form-label">Pan number<sup style="color:red;">*</sup></label>
            <div class="input-group">
                <input value="{{old('name')}}" type="text"  class="form-control border-start-0" id="pan_card_number_web" placeholder="Enter pan number" required>
            </div>
            @error('pan_card_number')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row mt-3">
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
            <label for="aadhar_number" class="form-label">Aadhar card number</label>
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
                <input type="file" class="form-control border-start-0" id="front_side_aadhar_file_web" required accept="image/*" data-file-type="Image">
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
                <input type="file" class="form-control border-start-0" id="back_side_aadhar_file_web" required accept="image/*" data-file-type="Image">
            </div>
            @error('back_side_aadhar_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="form-check form-switch">
                <label>Property Is Owned</label>
                <input type="checkbox" checked="" class="form-check-input property-check" name="is_property_own" value="1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch">
                <label>Property Is Rented</label>
                <input type="checkbox" class="form-check-input property-check" name="is_property_ranted" value="1">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch">
                <label>Property Is Named Of Family Member</label>
                <input type="checkbox" class="form-check-input property-check" name="is_property_name_family_member" value="1">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 electricity_bill_file">
            <label for="electricity_bill_file_web" class="form-label">Electricity Bill File</label>
            <div class="input-group">
                <input type="hidden" name="electricity_bill_file" id="electricity_bill_file">
                <input type="file" class="form-control border-start-0" id="electricity_bill_file_web" accept="image/*" data-file-type="Image">
            </div>
            @error('electricity_bill_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6 rent_agreement_stamp_file">
            <label for="rent_agreement_stamp_file_web" class="form-label">Rent agreement on 500 stamp</label>
            <div class="input-group">
                <input type="hidden" name="rent_agreement_stamp_file" id="rent_agreement_stamp_file">
                <input type="file" class="form-control border-start-0" id="rent_agreement_stamp_file_web" accept="image/*" data-file-type="Image">
            </div>
            @error('rent_agreement_stamp_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6 mt-3 property_document_file">
            <label for="property_document_file_web" class="form-label">Property Document</label>
            <div class="input-group">
                <input type="hidden" name="property_document_file" id="property_document_file">
                <input type="file" class="form-control border-start-0" id="property_document_file_web" accept="image/*" data-file-type="Image">
            </div>
            @error('property_document_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6 mt-3 noc_certificate_file">
            <label for="noc_certificate_file_web" class="form-label">Noc certificate</label>
            <div class="input-group">
                <input type="hidden" name="noc_certificate_file" id="noc_certificate_file">
                <input type="file" class="form-control border-start-0" accept="image/*" data-file-type="Image" id="noc_certificate_file_web">
            </div>
            @error('noc_certificate_file')
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
    document.addEventListener('DOMContentLoaded', function ()
    {
        // Select file input elements
        const nocCertificateFile = document.querySelector('.noc_certificate_file');
        const electricityBillFile = document.querySelector('.electricity_bill_file');
        const propertyDocumentFile = document.querySelector('.property_document_file');
        const rentAgreementStampFile = document.querySelector('.rent_agreement_stamp_file');

        // Function to hide all file inputs
        function hideAllFiles()
        {
            if (nocCertificateFile) nocCertificateFile.style.display = 'none';
            if (electricityBillFile) electricityBillFile.style.display = 'none';
            if (propertyDocumentFile) propertyDocumentFile.style.display = 'none';
            if (rentAgreementStampFile) rentAgreementStampFile.style.display = 'none';
        }

        // Initial hide all file inputs except electricity_bill_file
        hideAllFiles();
        if (electricityBillFile) electricityBillFile.style.display = 'block';

        // Add event listeners to checkboxes
        const checkboxes = document.querySelectorAll('.property-check');
        checkboxes.forEach(checkbox =>
        {
            checkbox.addEventListener('change', function ()
            {
                if (this.checked)
                {
                    // Uncheck all other checkboxes
                    checkboxes.forEach(otherCheckbox =>
                    {
                        if (otherCheckbox !== this)
                        {
                            otherCheckbox.checked = false;
                        }
                    });

                    // Show the appropriate file input based on the selected checkbox
                    hideAllFiles();
                    // alert(this.name);
                    if (this.name === 'is_property_own' && electricityBillFile)
                    {
                        electricityBillFile.style.display = 'block';
                    }
                    else if (this.name === 'is_property_ranted')
                    {
                        if(electricityBillFile) electricityBillFile.style.display = 'block';
                        if(propertyDocumentFile) propertyDocumentFile.style.display = 'block';
                        if(rentAgreementStampFile) rentAgreementStampFile.style.display = 'block';
                    }
                    else if (this.name === 'is_property_name_family_member')
                    {
                        if (electricityBillFile) electricityBillFile.style.display = 'block';
                        if (propertyDocumentFile) propertyDocumentFile.style.display = 'block';
                        if(propertyDocumentFile) propertyDocumentFile.classList.remove('mt-3');
                    }
                }
            });
        });
    });

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