//Basic Example
$("#example-basic").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  autoFocus: true,
});

// Basic Example with form
var form = $("#example-form");
form.validate({
  errorPlacement: function errorPlacement(error, element) {
    element.before(error);
  },
  rules: {
    confirm: {
      equalTo: "#password",
    },
  },
});
form.children("div").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  onStepChanging: function (event, currentIndex, newIndex) {
    form.validate().settings.ignore = ":disabled,:hidden";
    return form.valid();
  },
  onFinishing: function (event, currentIndex) {
    form.validate().settings.ignore = ":disabled";
    return form.valid();
  },
  onFinished: function (event, currentIndex) {
    alert("Submitted!");
  },
});

// Advance Example

var form = $("#example-advanced-form").show();

form
  .steps({
    headerTag: "h3",
    bodyTag: "fieldset",
    transitionEffect: "slideLeft",
    onStepChanging: function (event, currentIndex, newIndex) {
      // Allways allow previous action even if the current form is not valid!
      if (currentIndex > newIndex) {
        return true;
      }
      // Forbid next action on "Warning" step if the user is to young
      if (newIndex === 3 && Number($("#age-2").val()) < 18) {
        return false;
      }
      // Needed in some cases if the user went back (clean up)
      if (currentIndex < newIndex) {
        // To remove error styles
        form.find(".body:eq(" + newIndex + ") label.error").remove();
        form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
      }
      form.validate().settings.ignore = ":disabled,:hidden";
      return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex) {
      // Used to skip the "Warning" step if the user is old enough.
      if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
        form.steps("next");
      }
      // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
      if (currentIndex === 2 && priorIndex === 3) {
        form.steps("previous");
      }
    },
    onFinishing: function (event, currentIndex) {
      form.validate().settings.ignore = ":disabled";
      return form.valid();
    },
    onFinished: function (event, currentIndex) {
      alert("Submitted!");
    },
  })
  .validate({
    errorPlacement: function errorPlacement(error, element) {
      element.before(error);
    },
    rules: {
      confirm: {
        equalTo: "#password-2",
      },
    },
  });

// Dynamic Manipulation
$("#example-manipulation").steps({
  headerTag: "h3",
  bodyTag: "section",
  enableAllSteps: true,
  enablePagination: false,
});

//Vertical Steps

$("#example-vertical").steps({
  headerTag: "h3",
  bodyTag: "section",
  transitionEffect: "slideLeft",
  stepsOrientation: "vertical",
});

$(".tab-wizard").steps({
  headerTag: "h6",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onStepChanging: function (event, currentIndex, newIndex) {
    if (currentIndex > newIndex) {
      return true;
    }
    if (newIndex === 3 && Number($("#age").val()) < 18) {
      return false;
    }
    if (newIndex === 3) {
        var firstname = $("#firstname").val();
        var lastname = $("#lastname").val();
      var previewHTML = generatePreviewHTML(firstname, lastname);
      $(".tab-wizard .body:eq(3)").html(previewHTML);
      $(".tab-wizard .actions:eq(3)").show();
    }
    var form = $(".tab-wizard").show();
    // To remove error styles
    $(".body:eq(" + newIndex + ") label.error", form).remove();
    $(".body:eq(" + newIndex + ") .error", form).removeClass("error");

    form.validate().settings.ignore = ":disabled,:hidden";
    return form.valid();
  },
  onFinished: function (event, currentIndex) {
     event.preventDefault();
  var formData = new FormData(this);
  $.ajax({
    url: "/submit-kyc",
    type: "POST",
    data: formData,
        processData:false,
        contentType:false,
        cache:false,
        async:false,
    success: function (data) {
        if(data.status == 'SUCCESS'){
                    Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Kyc Form Submitted!',
              text: 'Wait for the admin to approve your request',
              confirmButtonText: 'OK',
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
        }
        else if(data.status == 'INFO')
        {
            Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Kyc Not Completed!',
              text: data.message,
              confirmButtonText: 'OK',
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
        }
        else
        {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: 'Kyc Not Completed!',
              text: data.message,
              confirmButtonText: 'OK',
            }).then((result) => {
              if (result.isConfirmed) {
                location.reload();
              }
            });
        }
    },
  });
  },
});
$.validator.addMethod("panNumber", function(value, element) {
  var pattern = /^([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}?$/;
  return this.optional(element) || pattern.test(value);
}, "Please enter a valid Indian PAN number.");
$.validator.addMethod(
  "aadharNumber",
  function (value, element) {
    return this.optional(element) || /^\d{12}$/.test(value);
  },
  "Please enter a valid 12-digit Aadhar number."
);
$.validator.addMethod("maxDateToday", function(value, element) {
  var selectedDate = new Date(value);
  var currentDate = new Date();
  currentDate.setHours(0, 0, 0, 0); // Set current time to midnight

  return selectedDate <= currentDate;
}, "Date can't be greater than today");

$(".tab-wizard").validate({
  // Other options and settings
  rules: {
    email: {
      required: true,
      email: true,
    },
    jobTitle2: {
      required: true,
      minlength: 3,
    },
    aadharNumber: {
      required: true,
      digits: true,
    },
    panNumber: {
      required: true,
      panNumber: true
    },
    dob :{
        required: true,
        date: true,
        maxDateToday: true,
    },
    // Add more fields with their respective validation rules
    
  },
  messages: {
    aadharNumber: {
      required: "Please enter your Aadhar number.",
    },
    panNumber: {
        required: "Please enter your PAN number.",
    },
    dob:{
         maxDateToday: "Date of birth cannot be greater than today's date.",
    }
  }

});


function generatePreviewHTML(firstname,lastname) {
  // Array to store the form field names
  var fieldNames = [
    "firstname",
    "lastname",
    "emailaddress",
    "phone",
    
    "fatherName",
    "aadharNumber",
    "panNumber",
    "address",
    "dob",
    
    
    "shopName",
    "pinCode",
    "district",
    "shopAddress",
    
    // Add more field names as needed
  ];

  // Initialize the HTML for the preview
  var html = '<h4>Preview</h4>';

  // Iterate over the field names and retrieve the corresponding values
  for (var i = 0; i < fieldNames.length; i++) {
    var fieldName = fieldNames[i];
    var fieldValue = $("#" + fieldName).val();

    // Append the field name and value to the HTML
    html += "<p>" + fieldName + ": " + fieldValue + "</p>";
  }

  return html;
}
$(".tab-wizard").on("submit", function (event) {
  // Prevent the default form submission
  event.preventDefault();

  // Get the form data
  var formData = $(this).serialize();

  // Perform the form submission using Ajax or any other method
  // Replace the URL with your actual form submission endpoint
  $.ajax({
    url: "submit-form.php",
    type: "POST",
    data: formData,
    success: function (response) {
      // Handle the form submission success
      // Display a success message, redirect the user, etc.
    },
    error: function (xhr, status, error) {
      // Handle the form submission error
      // Display an error message, show validation errors, etc.
    },
  });
});

var form = $(".validation-wizard").show();

$(".validation-wizard").steps({
  headerTag: "h6",
  bodyTag: "section",
  transitionEffect: "fade",
  titleTemplate: '<span class="step">#index#</span> #title#',
  labels: {
    finish: "Submit",
  },
  onStepChanging: function (event, currentIndex, newIndex) {
    return ( currentIndex > newIndex || (!(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), (form.validate().settings.ignore = ":disabled,:hidden"), form.valid()))
    );
  },
  onFinishing: function (event, currentIndex) {
    return (form.validate().settings.ignore = ":disabled"), form.valid();
  },
  onFinished: function (event, currentIndex) {
    swal(
      "Form Submitted!",
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed."
    );
  },
}),
  $(".validation-wizard").validate({
    ignore: "input[type=hidden]",
    errorClass: "text-danger",
    successClass: "text-success",
    highlight: function (element, errorClass) {
      $(element).removeClass(errorClass);
    },
    unhighlight: function (element, errorClass) {
      $(element).removeClass(errorClass);
    },
    errorPlacement: function (error, element) {
      error.insertAfter(element);
    },
    rules: {
      email: {
        email: !0,
      },
    },
  });
