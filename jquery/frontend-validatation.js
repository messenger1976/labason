$(document).ready(function(){ 
    // Setup form validation on the #rating-form element
    $("#signin_vendor").validate({
        // Specify the validation rules
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
				email : true
            },
            mobile: {
                required: true,
				digits: true,
                minlength: 10,
                maxlength: 10
            }, 
        },
        // Specify the validation error messages
        messages: {
            name: {
                required: "Enter Store Name ..."
            },
            email: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
            mobile: {
                required: "Enter Mobile Number ...",
  				digits:  "Enter Digits Only ...",
             	minlength: "Min 10 Digits ...",
                maxlength: "Max 10 Digits ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });
	
    $("#signin_customer").validate({
        // Specify the validation rules
        rules: {
            email: {
                required: true,
				email : true
            },
             new_pwd: {
                required: true,
                minlength: 6
            },
            conf_pwd: {
                required: true,
                minlength: 6,
				equalTo: "#new_pwd"
            }
       },
        // Specify the validation error messages
        messages: {
            email: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
             new_pwd: {
                required: "Please Enter New Password ...",
                minlength: "Atleast 6 Characters ..."
            },
            conf_pwd: {
                required: "Please Enter Confirm Password ...",
                minlength: "Atleast 6 Characters ...",
				equalTo: "Please Enter The Same Password As Above ..."
            }
       },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });
	
    $("#forgot-password").validate({
        // Specify the validation rules
        rules: {
            email: {
                required: true,
				email : true
            },
        },
        // Specify the validation error messages
        messages: {
            email: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });
	
    $("#change-password-form").validate({
        
        // Specify the validation rules
        rules: {
            cur_pwd: {
                required: true,
                minlength: 6
            },
            new_pwd: {
                required: true,
                minlength: 6
            },
            conf_pwd: {
                required: true,
                minlength: 6,
				equalTo: "#new_pwd"
            }
        },
        
        // Specify the validation error messages
        messages: {
            cur_pwd: {
                required: "Please Enter Current Password ...",
                minlength: "Atleast 6 Characters ..."
            },
            new_pwd: {
                required: "Please Enter New Password ...",
                minlength: "Atleast 6 Characters ..."
            },
            conf_pwd: {
                required: "Please Enter Confirm Password ...",
                minlength: "Atleast 6 Characters ...",
				equalTo: "Please Enter The Same Password As Above ..."
            }
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
		
    });
    $("#login_customer_authentication").validate({
        // Specify the validation rules
        rules: {
            username: {
                required: true,
				email : true
            },
            password: {
                required: true,
            }, 
        },
        // Specify the validation error messages
        messages: {
            username: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
            password: {
                required: "Enter Password ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });	
    $("#login_customer_vendor").validate({
        // Specify the validation rules
        rules: {
            email: {
                required: true,
				email : true
            },
            password: {
                required: true,
            }, 
        },
        // Specify the validation error messages
        messages: {
            email: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
            password: {
                required: "Enter Password ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });

    $("#password-change-form").validate({
        
        // Specify the validation rules
        rules: {
            old_password: {
                required: true,
                minlength: 6
            },
            new_password: {
                required: true,
                minlength: 6
            },
            conform_password: {
                required: true,
                minlength: 6,
				equalTo: "#new_password"
            }
        },
        
        // Specify the validation error messages
        messages: {
            old_password: {
                required: "Please Enter Current Password ...",
                minlength: "Atleast 6 Characters ..."
            },
            new_password: {
                required: "Please Enter New Password ...",
                minlength: "Atleast 6 Characters ..."
            },
            conform_password: {
                required: "Please Enter Confirm Password ...",
                minlength: "Atleast 6 Characters ...",
				equalTo: "Please Enter The Same Password As Above ..."
            }
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
		
    });

    $("#profile_edit_customer").validate({
        // Specify the validation rules
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email: {
                required: true,
				email : true
            },
            mobile: {
                required: true,
				digits: true,
                minlength: 10,
                maxlength: 10
            }, 
        },
        // Specify the validation error messages
        messages: {
            first_name: {
                required: "Enter First Name ..."
            },
            last_name: {
                required: "Enter Second Name ..."
            },
            email: {
                required: "Enter Email Id...",
				email : "Enter Valid Email Id ..."
            },
            mobile: {
                required: "Enter Mobile Number ...",
  				digits:  "Enter Digits Only ...",
             	minlength: "Min 10 Digits ...",
                maxlength: "Max 10 Digits ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });

    $("#profile_edit_vendor").validate({
        // Specify the validation rules
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            mobile: {
                required: true,
				digits: true,
                minlength: 10,
                maxlength: 10
            }, 
            contact_person_name: {
                required: true,
            },
        },
        // Specify the validation error messages
        messages: {
            first_name: {
                required: "Enter First Name ..."
            },
            last_name: {
                required: "Enter Second Name ..."
            },
            mobile: {
                required: "Enter Mobile Number ...",
  				digits:  "Enter Digits Only ...",
             	minlength: "Min 10 Digits ...",
                maxlength: "Max 10 Digits ..."
            },
            contact_person_name: {
                required: "Enter Contact Person Name...",
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });
	
	$("#shipping_form").validate({
        // Specify the validation rules
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            mobile: {
                required: true,
				digits: true,
                minlength: 10,
                maxlength: 10
            }, 
            address1: {
                required: true
            },
            area: {
                required: true
            },
            state: {
                required: true
            },
            city: {
                required: true
            },
            pincode: {
                required: true,
				digits: true
            },
        },
        // Specify the validation error messages
        messages: {
            first_name: {
                required: "Enter First Name ..."
            },
            last_name: {
                required: "Enter Last Name ..."
            },
            mobile: {
                required: "Enter Mobile Number ...",
  				digits:  "Enter Digits Only ...",
             	minlength: "Min 10 Digits ...",
                maxlength: "Max 10 Digits ..."
            },
            address1: {
                required: "Enter Address ..."
            },
            area: {
                required: "Enter Area ..."
            },
            state: {
                required: "Select State ..."
            },
            city: {
                required: "Select City ..."
            },
            pincode: {
  				digits:  "Enter Digits Only ...",
                required: "Enter Last Name ..."
            },
        },
        // Specify the css error messages
		highlight: function(element) {
			$(element).closest('.font-bold').addClass('error');
		}
    });

});// end document.ready