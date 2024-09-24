<style type="text/css">
.hidden {
    display: none;
}
</style>
@php
    $time_key = strtotime(date('Y-m-d'));
@endphp
<form id="serviceForm" enctype="multipart/form-data" class="row g-3" method="POST" >
    @csrf
    <input type="hidden" name="service_id" value="{{$service_id}}">
    <h6 class="mb-0 text-uppercase">Document Details</h6>
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="itr_form_type" class="form-label">Itr form type</label>
            <div class="input-group">
                <select name="itr_form_type" class="form-control" id="itr_form_type" required>
                    <option value="Business">Business</option>
                    <option value="Salary">Salary</option>
                </select>
                @error('itr_form_type')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <label for="gender" class="form-label">Gender</label>
            <div class="input-group">
                <select name="gender" class="form-control" id="gender" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                @error('gender')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="aadhar_number" class="form-label">Aadhar Number <sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="number" name="aadhar_number" class="form-control border-start-0" id="aadhar_number" placeholder="Aadar Number" required>
        </div>
        @error('aadhar_number')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="residence_number" class="form-label">Aadhar residence number<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="residence_number" class="form-control border-start-0" id="residence_number" placeholder="Aadar residence number" required>
        </div>
        @error('residence_number')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="locality_or_area" class="form-label">Aadhar locality area<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="locality_or_area" class="form-control border-start-0" id="locality_or_area" placeholder="Aadhar locality area" required>
        </div>
        @error('locality_or_area')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="road_or_street" class="form-label">Street name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="road_or_street" class="form-control border-start-0" id="road_or_street" placeholder="Aadar residence number" required>
        </div>
        @error('road_or_street')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="city_or_town_or_district" class="form-label">Aadhar City or district<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="city_or_town_or_district" class="form-control border-start-0" id="city_or_town_or_district" placeholder="Aadhar City or district" required>
        </div>
        @error('city_or_town_or_district')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="pin_code" class="form-label">Pin code<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="number" name="pin_code" class="form-control border-start-0" id="pin_code" placeholder="Pin code" required>
        </div>
        @error('pin_code')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="pan_card_number" class="form-label">Pan card Number<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="pan_card_number" class="form-control border-start-0" id="pan_card_number" placeholder="Pan card Number" required>
        </div>
        @error('pan_card_number')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="pan_card_name" class="form-label">Pan card name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="pan_card_name" class="form-control border-start-0" id="pan_card_name" placeholder="Pan card name" required>
        </div>
        @error('pan_card_name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="pan_card_father_name" class="form-label">Pan card father name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="text" name="pan_card_father_name" class="form-control border-start-0" id="pan_card_father_name" placeholder="Pan card father name" required>
        </div>
        @error('pan_card_father_name')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="pan_card_dob" class="form-label">Pan card dob<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
        <div class="input-group">
            <input type="date" name="pan_card_dob" class="form-control border-start-0" id="pan_card_dob" placeholder="Pan card dob" required>
        </div>
        @error('pan_card_dob')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <h6 class="mb-0 text-uppercase">Bank Details</h6>
    <div id="bank-details-container">
        <div class="row bank-details" id="bank-details-template">
            <div class="col-md-6">
                <label for="bank_name" class="form-label">Bank name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="text" name="bank_name[]" class="form-control border-start-0" placeholder="Enter bank name" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="account_number" class="form-label">Account number<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="account_number[]" class="form-control border-start-0" placeholder="Enter account number" required>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <label for="ifsc_code" class="form-label">Ifsc code<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="text" name="ifsc_code[]" class="form-control border-start-0" placeholder="Enter ifsc code" required>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <label for="account_type" class="form-label">Account type<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <select name="account_type[]" class="form-control border-start-0" required>
                        <option value="">Select account type</option>
                        <option value="Saving Account">Saving Account</option>
                        <option value="Current Account">Current Account</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <label class="form-label">File name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="hidden" name="bank_file_name[]" id="bank_file_name_{{$time_key}}" class="bank_file_name_hidden">
                    <input type="file" id="bank_file_name_web_{{$time_key}}" class="form-control border-start-0 file-uploads" data-file-type="Image" required>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="button" id="add-bank-details" class="btn btn-primary">Add Bank Details</button>
    </div>

    <hr>
    <h6 class="mb-0 text-uppercase">Investment Details</h6>
    <h6>Upload Your All investment details</h6>
    <div class="col-md-6">
        <label for="school_fees_file_web" class="form-label">School Fee File</label>
        <div class="input-group">
            <input type="hidden" name="school_fees_file" id="school_fees_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="school_fees_file_web" placeholder="Pan card name"  data-file-type="Image">
        </div>
        @error('school_fees_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="sf_amount" class="form-label">Amount</label>
        <div class="input-group">
            <input type="number" name="sf_amount" class="form-control border-start-0" id="sf_amount" placeholder="Enter amount">
        </div>
        @error('sf_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="lic_file_web" class="form-label">Lic file</label>
        <div class="input-group">
            <input type="hidden" name="lic_file" id="lic_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="lic_file_web" placeholder="Pan card name"  data-file-type="Image">
        </div>
        @error('lic_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="lic_amount" class="form-label">Lic Amount</label>
        <div class="input-group">
            <input type="number" name="lic_amount" class="form-control border-start-0" id="lic_amount" placeholder="Enter amount">
        </div>
        @error('lic_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="mutual_fund_file_web" class="form-label">Mutual fund file</label>
        <div class="input-group">
            <input type="hidden" name="mutual_fund_file" id="mutual_fund_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="mutual_fund_file_web" placeholder="Pan card name"  data-file-type="Image">
        </div>
        @error('mutual_fund_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="mf_amount" class="form-label">Mutual fund Amount</label>
        <div class="input-group">
            <input type="number" name="mf_amount" class="form-control border-start-0" id="mf_amount" placeholder="Enter amount">
        </div>
        @error('mf_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="sukanya_yohna_file_web" class="form-label">Sukanya Yojna file</label>
        <div class="input-group">
            <input type="hidden" name="sukanya_yohna_file" id="sukanya_yohna_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="sukanya_yohna_file_web" placeholder="Pan card name"  data-file-type="Image">
        </div>
        @error('sukanya_yohna_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="sy_amount" class="form-label">Sukanya Yojna Amount</label>
        <div class="input-group">
            <input type="number" name="sy_amount" class="form-control border-start-0" id="sy_amount" placeholder="Enter amount">
        </div>
        @error('sy_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="housing_loan_file_web" class="form-label">Housing loan file</label>
        <div class="input-group">
            <input type="hidden" name="housing_loan_file" id="housing_loan_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="housing_loan_file_web" placeholder="Pan card name"  data-file-type="Image">
        </div>
        @error('housing_loan_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="hl_amount" class="form-label">Housing loan Amount</label>
        <div class="input-group">
            <input type="number" name="hl_amount" class="form-control border-start-0" id="hl_amount" placeholder="Enter housing loan amount">
        </div>
        @error('hl_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="health_insurance_file_web" class="form-label">Health insurance file</label>
        <div class="input-group">
            <input type="hidden" name="health_insurance_file" id="health_insurance_file">
            <input type="file" class="form-control border-start-0 file-uploads" id="health_insurance_file_web" placeholder="Enter health insurance file"  data-file-type="Image">
        </div>
        @error('health_insurance_file')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="hi_amount" class="form-label">Health insurance Amount</label>
        <div class="input-group">
            <input type="number" name="hi_amount" class="form-control border-start-0" id="hi_amount" placeholder="Enter health insurance amount">
        </div>
        @error('hi_amount')
            <span class="text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div id="investment-container">
        <label for="other_investment" class="form-label">Other investment</label>
        <div class="investment-wrapper">
            <div class="row other-investment">
                <div class="col-md-3">
                    <label for="invest_name" class="form-label">Invest name</label>
                    <div class="input-group">
                        <input type="text" name="invest_name[]" class="form-control border-start-0" id="invest_name_{{$time_key}}"  placeholder="Invest name">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="invest_file_web_{{$time_key}}" class="form-label">Invest file</label>
                    <div class="input-group">
                        <input type="hidden" name="invest_file[]" id="invest_file_{{$time_key}}">
                        <input type="file" class="form-control border-start-0 file-uploads" id="invest_file_web_{{$time_key}}"  placeholder="Invest file"  data-file-type="Image">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="invest_amount" class="form-label">Invest Amount</label>
                    <div class="input-group">
                        <input type="number" name="invest_amount[]" class="form-control border-start-0" id="invest_amount_{{$time_key}}" placeholder="Invest Amount">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="button" id="add-investment" class="btn btn-primary">Add Investment</button>
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Other Income</h6>
    <div class="row mt-3">
        <div class="col-md-4">
            <label for="rent_income" class="form-label">Rent income</label>
            <div class="input-group">
                <input type="text" name="rent_income" class="form-control border-start-0" id="rent_income" placeholder="Enter rent income">
            </div>
            @error('rent_income')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="fdr_intrest" class="form-label">Intrest form fdr</label>
            <div class="input-group">
                <input type="text" name="fdr_intrest" class="form-control border-start-0" id="fdr_intrest" placeholder="Enter intrest form fdr">
            </div>
            @error('fdr_intrest')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="sbi_income" class="form-label">Sb account income</label>
            <div class="input-group">
                <input type="text" name="sbi_income" class="form-control border-start-0" id="sbi_income" placeholder="Enter sb account income">
            </div>
            @error('sbi_income')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Capital Gain</h6>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="form-check form-switch">
                <label>Sale of land,Building,Property</label>
                <input type="checkbox" class="form-check-input property-check" name="is_sale_land" value="1" checked>
            </div>
            @error('is_sale_land')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <div class="form-check form-switch">
                <label>Sale of share</label>
                <input type="checkbox" class="form-check-input property-check" name="is_sale_share" value="1">
            </div>
            @error('is_sale_share')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6 sale_deed_file_name">
            <label for="sale_deed_file_name_web" class="form-label">Sale deed file</label>
            <div class="input-group">
                <input type="hidden" name="sale_deed_file_name" id="sale_deed_file_name">
                <input type="file" class="form-control border-start-0 file-uploads" id="sale_deed_file_name_web"  data-file-type="Image">
            </div>
            @error('sale_deed_file_name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6 purchase_deed_file_name">
            <label for="purchase_deed_file_name_web" class="form-label">Purchase deed file</label>
            <div class="input-group">
                <input type="hidden" name="purchase_deed_file_name" id="purchase_deed_file_name">
                <input type="file" class="form-control border-start-0 file-uploads" id="purchase_deed_file_name_web"  data-file-type="Image">
            </div>
            @error('purchase_deed_file_name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6 mt-3 sale_share_file_name">
            <label for="sale_share_file_name_web" class="form-label">Purchase deed file</label>
            <div class="input-group">
                <input type="hidden" name="sale_share_file_name" id="sale_share_file_name">
                <input type="file" class="form-control border-start-0 file-uploads" id="sale_share_file_name_web"  data-file-type="Image">
            </div>
            @error('sale_share_file_name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <hr>
    <h6 class="mb-0 text-uppercase">Any other expenses on purchase property</h6>
    <div id="expenses-wrapper">
        <div class="row mt-3 expense-item">
            <div class="col-md-5">
                <label for="invest_name" class="form-label">Other expenses name</label>
                <div class="input-group">
                    <input type="text" name="other_expense_title[]" class="form-control border-start-0" placeholder="Enter other expenses name">
                </div>
            </div>
            <div class="col-md-5">
                <label for="invest_name" class="form-label">Amount</label>
                <div class="input-group">
                    <input type="text" name="other_expense_amount[]" class="form-control border-start-0" placeholder="Enter amount">
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <button type="button" id="add-expenses-wrapper" class="btn btn-primary">Add More</button>
    </div>

    <h6 class="mb-0 text-uppercase">Add Any other income</h6>
    <div id="income-wrapper">
        <div class="row mt-3 income-item">
            <div class="col-md-5">
                <label for="invest_name" class="form-label">Other income name</label>
                <div class="input-group">
                    <input type="text" name="income_title[]" class="form-control border-start-0" placeholder="Enter other income name">
                </div>
            </div>
            <div class="col-md-5">
                <label for="invest_name" class="form-label">Amount</label>
                <div class="input-group">
                    <input type="text" name="income_amount[]" class="form-control border-start-0" placeholder="Enter amount">
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <button type="button" id="add-income-wrapper" class="btn btn-primary">Add More</button>
    </div>
    <hr>

    <div id="business-section" class="mt-3">
        <h6 class="mb-0 text-uppercase">For business</h6>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="invest_name" class="form-label">Firm name<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="text" name="business_firm_name" class="form-control border-start-0" placeholder="Enter firm name" id="business_firm_name" required>
                </div>
                @error('business_firm_name')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="gst_number" class="form-label">Gst number</label>
                <div class="input-group">
                    <input type="text" name="gst_number" class="form-control border-start-0" placeholder="Enter gst number" id="gst_number">
                </div>
                @error('gst_number')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <h6 class="mb-0 text-uppercase mt-3">Sale & Turnover Amount</h6>
        <div class="row mt-3">
            <div class="col-md-6">
                <label for="cash_sale" class="form-label">cash sale<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="cash_sale" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="cash_sale" required>
                </div>
                @error('cash_sale')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="bank_sale" class="form-label">Bank sale<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="bank_sale" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="bank_sale" required>
                </div>
                @error('bank_sale')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mt-3">
                <label for="debtors" class="form-label">Debtors<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="debtors" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="debtors" required>
                </div>
                @error('debtors')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mt-3">
                <label for="creditors" class="form-label">Creditor<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="creditors" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="creditors" required>
                </div>
                @error('creditors')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mt-3">
                <label for="cash" class="form-label">Cash<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="cash" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="cash" required>
                </div>
                @error('cash')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="col-md-6 mt-3">
                <label for="stock" class="form-label">Stock<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                <div class="input-group">
                    <input type="number" name="stock" class="form-control border-start-0" placeholder="समझ में नहीं आ रहा है? कृपया 0 लिख दे |" id="stock" required>
                </div>
                @error('stock')
                    <span class="text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <hr>
    <h6 class="mb-0 text-uppercase">Self Declaration By Client</h6>

    <div class="row mt-3">
        <div class="col-md-6">
            <label for="firm_name" class="form-label">Client name</label>
            <div class="input-group">
                <input type="text" name="firm_name" class="form-control border-start-0" placeholder="Client name" id="firm_name">
            </div>
            @error('firm_name')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3 mt-5 itr-form-tab">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">
                        Regerster in ITR Portal ?
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">New User ?</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button" role="tab" aria-controls="pills-password" aria-selected="false">
                        Fogot Password ?
                    </button>
                </li>
            </ul>
            <div class="tab-content border p-3" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-12 my-3">
                            <div class="mb-3">
                                <label for="exampleInputText1" class="form-label">Username</label>
                                <input class="form-control" autocomplete="off" id="exampleInputText1" placeholder="Enter Username" name="itr_user_name" type="text" >
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputText2" class="form-label">Password<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
                                <input class="form-control" autocomplete="off" id="exampleInputText2" placeholder="Enter Password" name="itr_password" type="password"  required="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-12 my-3 d-flex justify-content-center">
                            <a class="btn btn-danger btn-lg" href="https://eportal.incometax.gov.in/iec/foservices/#/pre-login/register" target="_blank">Regester Now at Incom Tax Portal</a>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab" tabindex="0">
                    <div class="row">
                        <div class="col-md-12 my-3 d-flex justify-content-center">
                            <a class="btn btn-danger btn-lg" href="https://eportal.incometax.gov.in/iec/foservices/#/login" target="_blank">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <h6 class="mb-0 text-uppercase">Your Details</h6>
    <div class="row mt-3">
        <div class="col-md-4">
            <label for="itr_mobile" class="form-label">Mobile number<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="text" name="itr_mobile" class="form-control border-start-0" id="itr_mobile" placeholder="Enter mobile number" required="">
            </div>
            @error('itr_mobile')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="whatsapp_number" class="form-label">Whatsapp number<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="text" name="whatsapp_number" class="form-control border-start-0" id="whatsapp_number" placeholder="Enter whatsapp number" required="">
            </div>
            @error('whatsapp_number')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="itr_email" class="form-label">Registered Email ID<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="email" name="itr_email" class="form-control border-start-0" id="itr_email" placeholder="Enter email id" required="">
            </div>
            @error('itr_email')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <hr>
    <h6 class="mb-0 text-uppercase">Document File</h6>
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="front_side_aadhar_file_web" class="form-label">Aadhar card front<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="hidden" name="front_side_aadhar_file" id="front_side_aadhar_file">
                <input type="file" class="form-control border-start-0 file-uploads" id="front_side_aadhar_file_web" placeholder="Pan card name"  data-file-type="Image" required="">
            </div>
            @error('front_side_aadhar_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="back_side_aadhar_file_web" class="form-label">Aadhar card back<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="hidden" name="back_side_aadhar_file" id="back_side_aadhar_file">
                <input type="file" class="form-control border-start-0 file-uploads" id="back_side_aadhar_file_web"  data-file-type="Image" required="">
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
            <label for="pan_card_file_web" class="form-label">Pan card file<sup style="color:red;font-size: 15px;position: relative;top: 0;">*</sup></label>
            <div class="input-group">
                <input type="hidden" name="pan_card_file" id="pan_card_file">
                <input type="file" class="form-control border-start-0 file-uploads" id="pan_card_file_web"  data-file-type="Image" required="">
            </div>
            @error('pan_card_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="bank_statement_web" class="form-label">Bank account statement detail</label>
            <div class="input-group">
                <input type="hidden" name="bank_statement" id="bank_statement">
                <input type="file" class="form-control border-start-0 file-uploads" id="bank_statement_web"  data-file-type="Image">
            </div>
            @error('bank_statement')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <label for="form_16_file_web" class="form-label">Form number 16</label>
            <div class="input-group">
                <input type="hidden" name="form_16_file" id="form_16_file">
                <input type="file" class="form-control border-start-0 file-uploads" id="form_16_file_web"  data-file-type="Image">
            </div>
            @error('form_16_file')
                <span class="text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="miscellaneous_file_web" class="form-label">Miscellaneous Items</label>
            <div class="input-group">
                <input type="hidden" name="miscellaneous_file" id="miscellaneous_file">
                <input type="file" class="form-control border-start-0 file-uploads" id="miscellaneous_file_web"  data-file-type="Image">
            </div>
            @error('miscellaneous_file')
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

    document.getElementById('add-investment').addEventListener('click', function()
    {
        const investmentWrapper = document.querySelector('.investment-wrapper');
        const newInvestmentItem = investmentWrapper.querySelector('.other-investment').cloneNode(true);

        // Clear the input values in the cloned item
        newInvestmentItem.querySelectorAll('input').forEach(input => input.value = '');

        // Generate a dynamic ID for the invest_file input
        const check_date = new Date().getTime();
        const dynamicFileId = 'invest_file_web_' + check_date;
        const fileInput = newInvestmentItem.querySelector('input[type="file"]');
        const inputHidden = newInvestmentItem.querySelector('input[type="hidden"]');
        fileInput.id = dynamicFileId;
        inputHidden.id = 'invest_file_'+check_date;

        newInvestmentItem.classList.add('mt-3');

        // Create and append the remove button
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger remove-investment';
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', function()
        {
            this.closest('.other-investment').remove();
        });

        const removeButtonWrapper = document.createElement('div');
        removeButtonWrapper.className = 'col-md-3 d-flex align-items-end';
        removeButtonWrapper.appendChild(removeButton);

        // Append the remove button container to the new investment item
        newInvestmentItem.appendChild(removeButtonWrapper);

        // Append the new investment item to the wrapper
        investmentWrapper.appendChild(newInvestmentItem);
    });

    document.addEventListener('DOMContentLoaded', function ()
    {
        document.getElementById('add-bank-details').addEventListener('click', function ()
        {
            var container = document.getElementById('bank-details-container');
            var original = document.getElementById('bank-details-template');
            var clone = original.cloneNode(true);

            // Generate a unique ID for the clone
            var uniqueId = Date.now();

            // Reset the cloned inputs
            var inputs = clone.querySelectorAll('input');
            inputs.forEach(function (input) {
                if (input.type === 'text') {
                    input.value = '';
                } else if (input.type === 'file') {
                    input.value = null;
                    // Set the new id for file input and hidden input
                    input.id = 'bank_file_name_web_' + uniqueId;
                    var hiddenInput = clone.querySelector('.bank_file_name_hidden');
                    hiddenInput.id = 'bank_file_name_' + uniqueId;
                    hiddenInput.value = '';
                }
            });

            var selects = clone.querySelectorAll('select');
            selects.forEach(function (select) {
                select.selectedIndex = 0;
            });

            // Add the mt-3 class to the cloned element
            clone.classList.add('mt-3');

            // Add a remove button to the clone
            var removeBtnContainer = document.createElement('div');
            removeBtnContainer.classList.add('col-md-6', 'mt-3', 'd-flex', 'align-items-end');
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('btn', 'btn-danger', 'remove-bank-details');
            removeBtn.textContent = 'Remove';
            removeBtnContainer.appendChild(removeBtn);
            clone.appendChild(removeBtnContainer);

            // Append the clone to the container
            container.appendChild(clone);
        });

        document.getElementById('bank-details-container').addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-bank-details')) {
                e.target.closest('.bank-details').remove();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function ()
    {
        // Select file input elements
        const saleDeedFileName = document.querySelector('.sale_deed_file_name');
        const saleShareFileName = document.querySelector('.sale_share_file_name');
        const purchaseDeedFileName = document.querySelector('.purchase_deed_file_name');

        // Function to hide all file inputs
        function hideAllFiles()
        {
            if (saleDeedFileName) saleDeedFileName.style.display = 'none';
            if (saleShareFileName) saleShareFileName.style.display = 'none';
            if (purchaseDeedFileName) purchaseDeedFileName.style.display = 'none';
        }

        // Initial hide all file inputs except electricity_bill_file
        hideAllFiles();
        if (saleDeedFileName) saleDeedFileName.style.display = 'block';
        if (purchaseDeedFileName) purchaseDeedFileName.style.display = 'block';

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
                    if (this.name === 'is_sale_land')
                    {
                        if(saleDeedFileName) saleDeedFileName.style.display = 'block';
                        if(purchaseDeedFileName) purchaseDeedFileName.style.display = 'block';
                    }
                    else if (this.name === 'is_sale_share')
                    {
                        if(saleShareFileName) saleShareFileName.style.display = 'block';
                    }
                }
            });
        });
    });

    document.getElementById('add-expenses-wrapper').addEventListener('click', function()
    {
        const expenseWrapper = document.getElementById('expenses-wrapper');
        const newExpenseItem = expenseWrapper.querySelector('.expense-item').cloneNode(true);

        // Clear the input values in the cloned item
        newExpenseItem.querySelectorAll('input').forEach(input => input.value = '');

        // Create and append the remove button
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger remove-expense';
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', function()
        {
            this.closest('.expense-item').remove();
        });

        const removeButtonWrapper = document.createElement('div');
        removeButtonWrapper.className = 'col-md-2 d-flex align-items-end';
        removeButtonWrapper.appendChild(removeButton);

        newExpenseItem.appendChild(removeButtonWrapper);
        // Append the new expense item to the wrapper
        expenseWrapper.appendChild(newExpenseItem);
    });

    document.getElementById('add-income-wrapper').addEventListener('click', function()
    {
        const incomeWrapper = document.getElementById('income-wrapper');
        const newIncomeItem = incomeWrapper.querySelector('.income-item').cloneNode(true);

        // Clear the input values in the cloned item
        newIncomeItem.querySelectorAll('input').forEach(input => input.value = '');

        // Create and append the remove button
        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'btn btn-danger remove-income';
        removeButton.textContent = 'Remove';
        removeButton.addEventListener('click', function()
        {
            this.closest('.income-item').remove();
        });

        const removeButtonWrapper = document.createElement('div');
        removeButtonWrapper.className = 'col-md-2 d-flex align-items-end';
        removeButtonWrapper.appendChild(removeButton);

        newIncomeItem.appendChild(removeButtonWrapper);

        // Append the new income item to the wrapper
        incomeWrapper.appendChild(newIncomeItem);
    });

    document.getElementById('itr_form_type').addEventListener('change', function()
    {
        const businessSection = document.getElementById('business-section');
        const businessInputs = businessSection.querySelectorAll('input');

        if (this.value === 'Business')
        {
            businessSection.classList.remove('hidden');
            businessInputs.forEach(input => input.setAttribute('required', 'required'));
        }
        else
        {
            businessSection.classList.add('hidden');
            businessInputs.forEach(input => input.removeAttribute('required'));
        }
    });

    $(document).on('change','input[type="file"]', function()
    {
        var fileInput = $(this);
        var inputId = fileInput.attr('id');
        var formData = new FormData();
        
        hidden_file_input_id = inputId.replace("_web", "");
        var file = fileInput[0].files[0];
        
        formData.append('file_name', fileInput[0].files[0]);
        // formData.append('file_type',fileInput.attr('data-file-type'));
        formData.append('file_type','Image');
        if (file.type.includes('image'))
        {
            formData.append('file_type','Image');
        }
        else if (file.type === 'application/pdf')
        {
            formData.append('file_type','Pdf');
        }
        else if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        {
            formData.append('file_type','Pdf');
        }
        
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
                alert(xhr.responseJSON.message +'. Please contact to api service provider. File upload failed!!');
                // Handle the error
            }
        });
    });
$(document).ready(function() {
        $('#serviceForm').on('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('service_form.store') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert(response.success);
                    console.log(response);
                },
                error: function(xhr) {
                    // Handle error response
                    alert(xhr.responseText.error + 'Form submission failed!');
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>