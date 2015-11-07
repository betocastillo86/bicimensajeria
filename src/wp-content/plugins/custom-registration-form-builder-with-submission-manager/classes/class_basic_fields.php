<?php
class crf_basic_fields extends crf_basic_options
{
	function __construct() {
	
	}
	
	public function crf_field_captcha_error($errors)
	{
		//print_r($errors);
		$textdomain = 'custom-registration-form-pro-with-submission-manager';
		?>
         <!--HTML for showing error when recaptcha does not matches-->
		<div class="crf_captcha_error" align="center">
         <?php  _e( 'Sorry, you didn\'t enter the correct captcha code.', $textdomain ); ?> 
         
         </div>
        <br />
        <br />
        <br />
		<?php	
	}
	
	public function crf_field_captcha($publickey)
	{
		?>
        
         <div class="formtablee" align="center">
      <div class="crf_input crf_input_captcha">
      <div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
      </div>
      </div>
      <style>
	  @media(max-width: 390px) {
    .g-recaptcha {
        margin: 1px;
    }
}
	  
	  </style>
      <script>
	  jQuery(window).load(function() {
    var recaptcha = jQuery(".g-recaptcha");
	
    if(jQuery(window).width() < 391 ) {
        var newScaleFactor = recaptcha.parent().innerWidth() / 304;
        recaptcha.css('transform', 'scale(' + newScaleFactor + ')');
        recaptcha.css('transform-origin', '0 0');
    }
    else {
        recaptcha.css('transform', 'scale(1)');
        recaptcha.css('transform-origin', '0 0');
    }
});
	  
	  jQuery(window).resize(function() {
    var recaptcha = jQuery(".g-recaptcha");
    if(recaptcha.css('margin') == '1px') {
        var newScaleFactor = recaptcha.parent().innerWidth() / 304;
        recaptcha.css('transform', 'scale(' + newScaleFactor + ')');
        recaptcha.css('transform-origin', '0 0');
    }
    else {
        recaptcha.css('transform', 'scale(1)');
        recaptcha.css('transform-origin', '0 0');
    }
});
	  </script>
        
        <?php
			
	}
	
	
	public function crf_get_registration_form_field($id)
	{
	   $this->crf_get_custom_form_field_let_user_decide($id);
	   //$this->crf_get_custom_form_field_user_name();
	   $this->crf_get_custom_form_field_user_first_name();
	   $this->crf_get_custom_form_field_user_email();
	   $this->crf_get_custom_form_field_user_password();
	 	
	}
	
	public function crf_field_creation($id,$form_type)
	{
		   global $wpdb;
		   $textdomain = 'custom-registration-form-pro-with-submission-manager';
		   $crf_fields =$wpdb->prefix."crf_fields";

			$qry1 = "select * from $crf_fields where Form_Id = '".$id."' order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			foreach($reg1 as $row1)
			{
				if($row1->Value == '-1')
				{
					$key = sanitize_key($row1->Name).'_'.$row1->Id;
					$this->crf_get_custom_form_fields($row1,'');
				}
			}

		   
		   if($form_type=='reg_form') $this->crf_get_registration_form_field($id); 
		   

		   foreach($reg1 as $row1)
		   {
			   if($row1->Value != '-1')
			   {
				   $key = sanitize_key($row1->Name).'_'.$row1->Id;
				   $value = $row1->Value;
				   $this->crf_get_custom_form_fields($row1,$value);
			   }
		   }
		   $enable_captcha = $this->crf_get_global_option_value('enable_captcha');
		   if($enable_captcha=='yes')
		   {
			   	  $publickey = $this->crf_get_global_option_value('public_key');
				  $this->crf_field_captcha($publickey);
		   }
		   
	}
	
	
	public function crf_get_custom_form_fields($row,$value)
	{
		$function = @crf_get_custom_form_field_.$row->Type;
		$this->$function($row,$value);	
	}
	
	public function crf_get_custom_form_field_user_name()
	{
		$textdomain = 'custom-registration-form-pro-with-submission-manager';
		?>
        <div class="formtable">
        <div class="crf_label">
          <label for="user_login"><?php _e('Username',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required" data-msg="<?php _e('Username',$textdomain);?> es obligatorio">
          <input type="text" size="20" onblur="javascript:validete_userName();" onkeyup="javascript:validete_userName();" onfocus="javascript:validete_userName();" onchange="javascript:validete_userName();" value="<?php echo (!empty($_POST['user_name']))?  $_POST['user_name']: ''; ?>" class="input" id="user_name" name="user_name">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
        </div>
      </div>
        <?php
	}

	public function crf_get_custom_form_field_user_first_name()
	{
		$textdomain = 'custom-registration-form-pro-with-submission-manager';
		?>
		<div class="formtable">
			<div class="crf_label">
				<label for="user_first_name"><?php _e('First Name',$textdomain);?>
				</label>
			</div>
			<div class="crf_input crf_required" data-msg="<?php _e('First Name',$textdomain);?> es obligatorio">
				<input type="text" size="20"  value="<?php echo (!empty($_POST['user_first_name']))?  $_POST['user_first_name']: ''; ?>" class="input" id="user_first_name" name="user_first_name">
				<div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
			</div>
		</div>
		<?php
	}


	
	public function crf_get_custom_form_field_captcha()
	{
		?>
        <div class="formtablee" align="center"><div class="crf_input crf_input_captcha"> <?php echo recaptcha_get_html($publickey, $error); ?> </div></div>
      <div class="reg_frontErr custom_error crf_error_text" id="divrecaptcha_response_field" style="display:none;"></div>
        <?php
		
	}
	
	public function crf_get_custom_form_field_user_email()
	{
		$textdomain = 'custom-registration-form-pro-with-submission-manager';
		?>
        <div class="formtable">
        <div class="crf_label">
          <label for="user_email"><?php _e('E-mail',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required crf_email" data-msg="<?php _e('E-mail',$textdomain);?> es obligatorio" >
          <input type="text" onblur="javascript:validete_email();" onkeyup="javascript:validete_email();" onfocus="javascript:validete_email();" onchange="" size="25" value="<?php echo (!empty($_POST['user_email']))?  $_POST['user_email']: ''; ?>" class="input" id="user_email" name="user_email">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="emailErr"></div>
        </div>
      </div>
        <?php
	}
	
	public function crf_get_custom_form_field_let_user_decide($id)
	{
		$form_options = $this->crf_get_form_option_value('form_option',$id);
		$form_option = maybe_unserialize($form_options);
	   if(isset($form_option['user_role_label']))
	   $user_role_label = $form_option['user_role_label'];
	   if(isset($form_option['user_role_options']))
	   $user_role_options = $form_option['user_role_options'];
	   if(isset($form_option['let_user_decide']))
	   $let_user_decide = $form_option['let_user_decide'];
	   if(isset($let_user_decide) && $let_user_decide==1 && isset($user_role_options)):
		?>
        <div class="formtable">
          <div class="crf_label">
            <label for="user_role"><?php echo $user_role_label;?></label>
          </div>
          <div class="crf_input crf_radiorequired">
            <?php 
									
									foreach($user_role_options as $radio)
									{?>
            <label><?php echo $radio; ?></label>
            <input type="radio" class="regular-text" value="<?php echo $radio;?>" id="user_role" name="user_role">
            <?php } ?>
            <div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
          </div>
        </div>
        <?php	
		endif;
	}
	
	public function crf_get_custom_form_field_analytics($key)
	{
		?>
        <input type="hidden" value="<?php echo time();?>" name="crf_timestamp" />
  		<input type="hidden" value="<?php echo $key;?>" name="crf_key" />
        <?php
	}
	
	public function crf_get_custom_form_field_user_password()
	{
		$textdomain = 'custom-registration-form-pro-with-submission-manager';
		$pwd_show = $this->crf_get_global_option_value('autogeneratedepass');
		if($pwd_show=='yes'):
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		?>
          <input id="inputPassword" name="user_pass" type="hidden" value="<?php echo $random_password; ?>" style="width:100%" />
          <input id="user_confirm_password" name="user_confirm_password" value="<?php echo $random_password; ?>" type="hidden"/>
          <div id="complexity" class="default" style="display:none !important; visibility:hidden; height:0px;"></div>
          <?php /*<div id="password_info" class="password-pro" style="display:none !important;"><?php _e('At least 7 characters please!',$textdomain);?></div>*/ ?>
		<?php
		else:
		?>
        <div class="formtable">
        <div class="crf_label">
          <label for="user_password"><?php _e('Password',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required crf_password" data-msg="<?php _e('Password',$textdomain);?> es obligatorio">
          <input id="inputPassword" name="user_pass" type="password" onfocus="javascript:document.getElementById('user_confirm_password').value = '';"  style="width:100%" />
          <div id="complexity" class="default" style="display:none;"></div>
          <?php /* <div id="password_info" class="password-pro"><?php _e('At least 7 characters please!',$textdomain);?></div>*/ ?>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          
        </div>
      </div>
			<?php /*
      <div class="formtable">
        <div class="crf_label">
          <label for="user_confirm_password"><?php _e('Confirm Password',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required crf_confirmpassword">
          <input id="user_confirm_password" name="user_confirm_password" type="password"/>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          <!--<div class="reg_frontErr crf_error_text" id="divuser_confirm_password" style="display:none;"><?php _e('Enter the password again to confirm',$textdomain);?></div>-->
        </div>
      </div>*/ ?>
        <?php
		endif;
	}

	public function crf_get_custom_form_field_text($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_required';?>" data-msg="<?php echo $row1->Name;?> es obligatorio">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
		
	}
	
	public function crf_get_custom_form_field_heading($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
		<div class="formtable crf_heading">
			  <h1 name="<?php echo $key;?>" class="<?php echo $row1->Class;?>"><?php echo $row1->Value;?></h1>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_paragraph($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable crf_paragraph">
	
			  <p name="<?php echo $key;?>" class="<?php echo $row1->Class;?>"><?php echo $row1->Option_Value;?></p>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_term_checkbox($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label <?php if($row1->Require==1)echo 'crf_termboxrequired';?>">
				<input type="checkbox" value="<?php echo 'yes';?>" id="<?php echo $key;?>" name="<?php echo $key;?>"  class="regular-text <?php echo $row1->Class;?>">
	   <label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1)echo '<sup class="crf_estric">*</sup>';?></label>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
				<textarea disabled rows="4" class="textareaa"><?php echo $row1->Description;?></textarea>
				
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_DatePicker($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_required';?>"  data-msg="<?php echo $row1->Name;?> es obligatorio">
				<input type="text" class="MyDate regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_email($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf_email <?php if($row1->Require==1)echo 'crf_required';?>"  data-msg="<?php echo $row1->Name;?> es obligatorio">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_number($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf_number <?php if($row1->Require==1)echo 'crf_required';?>"  data-msg="<?php echo $row1->Name;?> es obligatorio">
				<input type="text" class="crf_number regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_textarea($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_textarearequired';?>">
				<textarea  class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" cols="<?php echo $row1->Cols;  ?>" rows="<?php echo $row1->Rows;  ?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>><?php echo $value; ?></textarea>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_radio($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		$array_value = explode(',',$value);
				?>
			<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_radiorequired';?>">
				<?php 
										$arr_radio = explode(',',$row1->Option_Value);
										foreach($arr_radio as $radio)
										{?>
				<label><?php echo $radio; ?></label>
				<input type="radio" class="regular-text  <?php echo $row1->Class;?>" value="<?php echo $radio;?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> id="<?php echo $key;?>" name="<?php echo $key;?>"  <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php } ?>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_checkbox($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		/*if($value == $row1->Value)
		{
			$arr_value = $value;	
		}*/
		$array_value = explode(',',$value);
			   ?>
			<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name; ?></label>
			  </div>
			  <div class="crf_checkbox <?php if($row1->Require==1)echo 'crf_checkboxrequired';?>">
				<?php 
				$arr_radio = explode(',',$row1->Option_Value);
				$radio_count = 1;
				foreach($arr_radio as $radio)
				{
					if($radio=='chl_other')
					{
						?>
						 <label><?php echo 'Other'; ?></label>
				<input type="text" class="regular-text <?php echo $row1->Class;?>" value="" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>">
						<?php
						continue;
					}
					
					?>
				<label><?php echo $radio; ?></label>
				<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php $radio_count++; 
				} ?>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
	   
		<?php
	}

	public function crf_get_custom_form_field_file($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable filewrapper">
				<div class="crf_label">
				  <label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
				</div>
				<div class="crf_input crf_contact_attachment">
				  <div class="fileinput crf_file">
				  <input type="file" class="regular-text <?php echo $row1->Class;?>" name="<?php echo $key.'[]';?>" data-filter-placeholder="<?php echo trim($row1->Option_Value); ?>" />
				  <?php if(get_option('ucf_repeatfilefields')=='yes'): ?>
				  <a><span class="add" onClick="addextrafile(this)">Add</span></a><a class="removebutton"><span class="remove" onClick="removeextrafile(this)">Remove</span></a>
				  <?php endif; ?>
				  <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
				  </div>
				</div>
			  </div>
		<?php
	}

	public function crf_get_custom_form_field_select($row1,$value)
	{
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf_select <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				  <?php
				  $arr = explode(',',$row1->Option_Value);
				  foreach($arr as $ar)
				  {
					  ?>
				  <option value="<?php echo $ar;?>" <?php if($ar==$value)echo 'selected';?>><?php echo $ar;?></option>
				  <?php	
				  }
				  ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_country($row1,$value)
	{
		
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf_select crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php include 'country_option_list.php'; ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}

	public function crf_get_custom_form_field_timezone($row1,$value)
	{
		
		$key = sanitize_key($row1->Name).'_'.$row1->Id;
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf_select crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php include 'time_zone_option_list.php'; ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	
}

?>