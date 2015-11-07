<script>
// JavaScript Document
jQuery(document).ready(function () 
{
	//for date picker
	jQuery('.MyDate').datepicker({
		dateFormat: 'yy-mm-dd'
	});
});
	
jQuery('#crf_contact_form').submit(function () 
{
	//email validation start for custom field	
	var email_val = "";
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.custom_error').html('');
	jQuery('.custom_error').hide();
	jQuery('.customcrferror').html('');
	
	var form_type = jQuery('input[name=form_type]').val();
	if(form_type == 'reg_form')
	{
		var password = jQuery('#inputPassword').val();
		var confirmpassword = jQuery('#user_confirm_password').val();
		var passwordlength = password.length;
		if(password !="")
		{
			/*if(passwordlength < 7)
			{
				jQuery('.crf_password').children('.custom_error').html('Your password should be at least 7 characters long.');
				jQuery('.crf_password').children('.custom_error').show();
			}
			if(password != confirmpassword)
			{
				jQuery('.crf_confirmpassword').children('.custom_error').html('Password and confirm password do not match.');
				jQuery('.crf_confirmpassword').children('.custom_error').show();
			}*/
		}
	}
	
	jQuery('.crf_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('.custom_error').html('Ingresa un correo valido.');
			jQuery(this).children('.custom_error').show();
		}
	});
	

	jQuery('.crf_number').each(function (index, element) { //Validation for number type custom field
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('.custom_error').html('Ingresa un numero valido');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('input').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html(jQuery(element).attr('data-msg'));
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_select_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('select').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_textarearequired').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('textarea').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_checkboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	jQuery('.crf_termboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	jQuery('.crf_file').each(function (index, element) {
			var val = jQuery(this).children('input').val().toLowerCase();
			var allowextensions = jQuery(this).children('input').attr('data-filter-placeholder');
			if(allowextensions=='')
			{
				allowextensions = '<?php echo get_option('ucf_allowfiletypes','jpg|jpeg|png|gif|doc|pdf|docx|txt|psd'); ?>';
			}
			var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
			if(!(regex.test(val)) && val!="") {
			
				jQuery(this).children('.custom_error').html('<?php _e('This file type is not allowed.',$textdomain);?>');
                jQuery(this).children('.custom_error').show();
			}
        });
	
	jQuery('.crf_radiorequired').each(function (index, element) { //Validation for number type custom field
	var radiolenght = jQuery(this).children('input[type="radio"]:checked');
	
	var atLeastOneIsChecked = radiolenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	var b = '';
	b = jQuery('.custom_error').each(function () {
		var a = jQuery(this).html();
		b = a + b;
		jQuery('.customcrferror').html(b);
	});
	var error = jQuery('.customcrferror').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
});

jQuery('.input-box').addClass('crf_input');
jQuery('.lable-text').addClass('crf_label');
jQuery('.crf_required').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_select_required').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_radiorequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_checkboxrequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_textarearequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');


function addextrafile(a) 
{
	var b = jQuery(a).parents('.fileinput').clone();
	jQuery(a).parents('.filewrapper .crf_contact_attachment').append(b);
}

function removeextrafile(a)
{
	jQuery(a).parents('.fileinput').remove();
}

</script>