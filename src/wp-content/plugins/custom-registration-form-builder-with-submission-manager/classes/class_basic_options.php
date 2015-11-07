<?php
class crf_basic_options
{
	
		public function crf_add_option($fieldname,$value)
		{
		  global $wpdb;
		  $crf_option=$wpdb->prefix."crf_option";
		  $update="update $crf_option set `value`='".$value."' where fieldname='".$fieldname."'";
		  $wpdb->query($update);
		}
		
		public function checkfieldname($fieldname,$value)
		{
			global $wpdb;
			$crf_option=$wpdb->prefix."crf_option";
			$select="select `value` from $crf_option where fieldname='".$fieldname."' and `value`='".$value."'";
			$data = $wpdb->get_var($select);
			
			if($data==$value)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		
		public function check_crf_form_expiration($form_option,$id)
		{
			global $wpdb;
			$message = "";
			$crf_submissions=$wpdb->prefix."crf_submissions";
			$auto_expires = @$form_option['auto_expires'];
			$expiry_type = @$form_option['expiry_type'];
			$submission_limit = @$form_option['submission_limit'];
			$expiry_date = @$form_option['expiry_date'];
			$expiry_message = @$form_option['expiry_message'];
			if(isset($auto_expires) && $auto_expires==1) 
			{
				
				if($expiry_type=='submission' || $expiry_type=='both' )
				{
					  $total = $wpdb->get_var( "SELECT count(distinct(submission_id)) FROM $crf_submissions where form_id ='".$id."'" );	
					  if($submission_limit<=$total)
					  {
							$form_expired = 1;  
					  }
				}
				
				if($expiry_type=='date' || $expiry_type=='both')
				{
					$today = date("Y-m-d");	
					$today_time = strtotime($today);
					$expire_time = strtotime($expiry_date);
					
					if ($expire_time < $today_time) 
					{  
						$form_expired = 1;
					}	
				}
				
				if(isset($form_expired) && $form_expired==1)
				{
					$message = '<div id="crf-form">
				  <div id="main-crf-form">
					<div class="main-edit-profile">'. $expiry_message . '<br />
					  <br />
					</div>
				  </div>
				</div>';
				return $message;
				}
				else
				{
					$message ="";
					return $message;
						
				}
			}else{return $message;}
			
		}
		
		public function crf_get_global_option_value($fieldname)
		{
			global $wpdb;
			$crf_option=$wpdb->prefix."crf_option";
			$select="select `value` from $crf_option where fieldname='".$fieldname."'";
			$data = $wpdb->get_var($select);
			return $data;
			
		}
		
		public function crf_get_form_option_value($fieldname,$id)
		{
			global $wpdb;
			$crf_forms=$wpdb->prefix."crf_forms";
			$qry="SELECT $fieldname FROM $crf_forms WHERE id=".$id;
			$value = $wpdb->get_var($qry);
			return $value;
			
		}
		
		public function crf_get_country_name($ip)
		{
			  $location = @file_get_contents('http://freegeoip.net/json/'.$ip);
			  $jsondetails    = json_decode($location);
			  $countryname = $jsondetails->country_name;
			  unset($jsondetails);
			  return $countryname;
			  
		}
		
		public function crf_get_browser_name($ExactBrowserNameUA)
		{
			if(strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")) {
					// OPERA
					$ExactBrowserNameBR="Opera";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "chrome/")) {
					// CHROME
					$ExactBrowserNameBR="Chrome";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "msie")) {
					// INTERNET EXPLORER
					$ExactBrowserNameBR="Internet Explorer";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "firefox/")) {
					// FIREFOX
					$ExactBrowserNameBR="Firefox";
				} elseif (strpos(strtolower($ExactBrowserNameUA), "safari/") and strpos(strtolower($ExactBrowserNameUA), "opr/")==false and strpos(strtolower($ExactBrowserNameUA), "chrome/")==false) {
					// SAFARI
					$ExactBrowserNameBR="Safari";
				} else {
					// OUT OF DATA
					$ExactBrowserNameBR="Other";
				};	
				return $ExactBrowserNameBR;
		}
		
		public function crf_get_entry_attachment($formid,$id)
		{
				/*file addon start */
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
				  $qry1 = "select * from $crf_fields where Form_Id= '".$formid."' and Type in('file') order by ordering asc";
				  $reg1 = $wpdb->get_results($qry1);
				  if(!empty($reg1))
				  {
					  $attachment_html = "";
				   foreach($reg1 as $row1)
				   {
					  if(!empty($row1))
					  {
						  $Customfield = $this->crf_get_field_key($row1);
						  $value = $this->crf_submision_field_value($id,$Customfield); 
						  $values = explode(',',$value);
						  
							  $attachment_html .=  '<div class="field-labal" ><p class="entry_heading" >'.$row1->Name.':</p>';	
							  foreach($values as $fileid)
							  {
								$attachment_html .='<div class="entry_Value"><ul>';
								$attachment_html .= '<li class="attachment_link">'.wp_get_attachment_link($fileid,'full',false,true,false).'</li>';
								$attachment_html .='<li class="file_title">'.get_the_title( $fileid ).'</li>';
								$attachment_html .= '<li class="Download"><a href="'.wp_get_attachment_url( $fileid ).'">Download</a></li>';
								$attachment_html .='<div class="clear"></div></ul><div class="clear"></div></div>';
							  }
							  $attachment_html .='</div>';
					  }
				   }
				  }
				  return $attachment_html;
			/*file addon end */	
		}
		
		public function crf_create_user($value,$role)
		{
			$user_first_name = $value['user_first_name'];
			$user_name = $value['user_email']; // receiving username
			$user_email = $value['user_email']; // receiving email address
			$inputPassword = $value['user_pass']; // receiving password
			$user_id = username_exists( $user_name ); // Checks if username is already exists.
			
			if ( !$user_id and email_exists($user_email) == false )//Creates password if password auto-generation is turned on in the settings
			{
				$user_id = wp_create_user( $user_name, $inputPassword, $user_email );//Creates new WP user after successful registration
				$user_id = wp_update_user( array( 'ID' => $user_id, 'role' => $role ) );
				//Actualiza a las malas el nombre
				update_user_meta( $user_id, 'first_name', $user_first_name );
			}
			return $user_id;
		}
		
		public function crf_update_stats($post,$id)
		{
			global $wpdb;
			$crf_stats=$wpdb->prefix."crf_stats";
			$stats = $wpdb->get_row( "SELECT * FROM $crf_stats where form_id ='".$id."' and stats_key='".$post['crf_key']."'");
			$stats_details = maybe_unserialize($stats->details);
			$stats_details['submitted'] = "yes";
			$stats_details['submit_time'] = time();
			$stats_details['total_time'] = $stats_details['submit_time']-$stats_details['timestamp'];
			$stats_final_details = maybe_serialize($stats_details);
			$stats_update = "update $crf_stats set details ='".$stats_final_details."' where id=".$stats->id;
			$wpdb->query($stats_update);	
		}
		
		public function set_crf_user_role($id,$post,$form_option)
		{
			//echo var_dump($form_option);
			if(isset($form_option['let_user_decide']))
			$let_user_decide = $form_option['let_user_decide'];
			if(isset($form_option['user_role_options']))
			$user_role_options = $form_option['user_role_options'];
			
			if(!isset($let_user_decide) || $let_user_decide=="")
			{
				
				if(isset($form_option['user_role']))
				$role = $form_option['user_role'];
				if(!isset($role)|| $role==""){ $role = 'subscriber'; } //Defines default role if there is not shortcode in registration form
			}
			else
			{
				if(isset($post['user_role']) && in_array($post['user_role'],$user_role_options))
				{
					$role = $post['user_role'];
				}
				else
				{ 
					$role = 'subscriber'; 
				} //Defines default role if there is not shortcode in registration form	
			}
			return $role;
				
		}
		public function crf_insert_form_entry($post,$id,$files,$server)
		{
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$crf_submissions=$wpdb->prefix."crf_submissions";
			$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type not in('heading','paragraph') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			$entry= array();
			$form_type = $this->crf_get_form_option_value('form_type',$id);
			$last_insert_id = $wpdb->get_var("SELECT max(`submission_id`) FROM $crf_submissions");
			$submission_id = $last_insert_id +1;
			
			$userip = $this->crf_get_global_option_value('userip');
			$autoapproval = $this->crf_get_global_option_value('userautoapproval');
			$form_options = $this->crf_get_form_option_value('form_option',$id);
			$form_option = maybe_unserialize($form_options);
			$this->crf_insert_submission($submission_id,$id,'form_type',$form_type);
			$this->crf_insert_submission($submission_id,$id,'user_approval',$autoapproval);
			
			if($form_type=='reg_form')
			{
					$user_name =  $post['user_name'];
					$user_email =  $post['user_email'];
					$user_pass =  $post['user_pass'];
					$role= $this->set_crf_user_role($id,$post,$form_option);
					$user_email = $post['user_email'];
					$this->crf_insert_submission($submission_id,$id,'user_name',$user_name);
					$this->crf_insert_submission($submission_id,$id,'user_email',$user_email);
					$this->crf_insert_submission($submission_id,$id,'user_pass',$user_pass);
					$this->crf_insert_submission($submission_id,$id,'role',$role);
					
			}
			
			if(!empty($reg1))
			{
			 foreach($reg1 as $row1)
			 {
				if(!empty($row1))
				{
					/*file addon start */
					$Customfield = $this->crf_get_field_key($row1);
					
					if ($row1->Type=='file') 
					{
						$filefield = $files[$Customfield];
						if(is_array($filefield))
						{
									
							for( $i =0; $i<=count($filefield['name']); $i++ ) 
							{
								$file = array(
											  'name'     => @$filefield['name'][$i],
											  'type'     => @$filefield['type'][$i],
											  'tmp_name' => @$filefield['tmp_name'][$i],
											  'error'    => @$filefield['error'][$i],
											  'size'     => @$filefield['size'][$i]
											);
											
								if (@$filefield['error'][$i] === 0)
								{
												
									  if ( ! function_exists( 'wp_handle_upload' ) )
									  {
										  require_once( ABSPATH . 'wp-admin/includes/file.php' );
									  }
									  
									  $upload_overrides = array( 'test_form' => false );
									  $movefile = wp_handle_upload( $file, $upload_overrides );
									  if ( $movefile )
									  {
										  // $filename should be the path to a file in the upload directory.
										  $filename = $movefile['file'];
										  // The ID of the post this attachment is for.
										  $parent_post_id = 0;
										  // Check the type of tile. We'll use this as the 'post_mime_type'.
										  $filetype = wp_check_filetype( basename( $filename ), null );
										  // Get the path to the upload directory.
										  $wp_upload_dir = wp_upload_dir();
										  // Prepare an array of post data for the attachment.
										  $attachment = array(
				  
											  'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
				  
											  'post_mime_type' => $filetype['type'],
				  
											  'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				  
											  'post_content'   => '',
				  
											  'post_status'    => 'inherit'
				  
										  );
										  // Insert the attachment.
										  $attach_id[] = wp_insert_attachment( $attachment, $filename, $parent_post_id );
										  
									  }
								  
								  
							 }
								
									
							}
						}
						if(isset($attach_id)):
						$attach_ids = implode(',',$attach_id);
						$this->crf_insert_submission($submission_id,$id,$Customfield,$attach_ids);
						unset($attach_id);
						endif;
					}
					else
					if(isset($post[$Customfield]))
					{	
						if(is_array($post[$Customfield]))
						{
							$value = implode(',',$post[$Customfield]);	
						}
						else
						{
							$value = $post[$Customfield];	
						}
					$this->crf_insert_submission($submission_id,$id,$Customfield,$value);
						//$entry[$Customfield] =  $post[$Customfield];
					}
					/*file addon end */
				}
			 }
			}
			
			if($userip=='yes')
			{
				$this->crf_insert_submission($submission_id,$id,'User_IP',$server['REMOTE_ADDR']);
				$this->crf_insert_submission($submission_id,$id,'Browser',$server['HTTP_USER_AGENT']);
			}
			$time = time();
			$this->crf_insert_submission($submission_id,$id,'entry_time',$time);
			return $submission_id;
		}
		
		public function crf_insert_user_data($id,$post,$user_id)
		{
				/*Insert custom field values if displayed in registration form*/
			global $wpdb;
			$crf_fields=$wpdb->prefix."crf_fields";
			$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type not in('heading','paragraph') order by ordering asc";
			$reg1 = $wpdb->get_results($qry1);
			if(!empty($reg1))
			{
			 foreach($reg1 as $row1)
			 {
				if(!empty($row1))
				{
					//$Customfield = sanitize_key($row1->Name).'_'.$row1->Id;
					$Customfield = sanitize_key($row1->Name);
					if(!isset($prev_value)) $prev_value='';
					if(!isset($post[$Customfield]))$post[$Customfield]='';
					add_user_meta( $user_id, $Customfield, $post[$Customfield], true );
					update_user_meta( $user_id, $Customfield, $post[$Customfield], $prev_value );
				}
			 }
			}	
		}
		
		
		public function crf_get_redirect_url($id,$redirect_option)
		{
			global $wpdb;
			$url="";
			$crf_forms=$wpdb->prefix."crf_forms";
			if($redirect_option=='url')
			{
				$qry="SELECT redirect_url_url FROM $crf_forms WHERE id=".$id;
				$url = $wpdb->get_var($qry);	
			}
			
			if($redirect_option == 'page')
			{
				$qry="SELECT redirect_page_id FROM $crf_forms WHERE id=".$id;
				$page_id = $wpdb->get_var($qry);	
				$url =  get_permalink($page_id); 
			}
			return $url;	
		}
		
		public function crf_send_admin_notification($entry_id,$id)
		{
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
				$crf_submissions=$wpdb->prefix."crf_submissions";
				$form_name = $this->crf_get_form_option_value('form_name',$id);
				$admin_email = $this->crf_get_global_option_value('adminemail');
				$notification_message = "";
				$from_email_address = $this->crf_get_global_option_value('from_email');
				if($from_email_address=="")
				{
					$from_email_address = get_option('admin_email');	
				}
				$qry = "select * from $crf_submissions where field!='user_pass' and submission_id=".$entry_id;	
				$entry = $wpdb->get_results($qry);
				
				if(!empty($entry))
				{
					$notification_message .= '<html><body><table cellpadding="10">';
					foreach($entry as $val) 
					{
						$key = $val->field;
						$value = $val->value;
						if(is_array($value))
						{
							$value = implode(',',$val);	
						}
						
						$entryval = str_replace("_"," ",$key);
										
						$fields= explode("_", $key);
						$fieldid = $fields[count($fields)-1];
						if(is_numeric($fieldid))
						{
							$nameqry = "select Name from $crf_fields where id=".$fieldid;
							$entryval = $wpdb->get_var($nameqry);
						}
						
						$notification_message .= '<tr><td><strong>'.$entryval.'</strong>: </td><td>'.$value.'</td></tr>';
						
					}
					$notification_message .= '</table></body></html>';
				}
				
					/*$headers = "From: " . $user_email . "\r\n";
					$headers .= "Reply-To: ".$user_email. "\r\n";*/
					$headers2 = 'From:'.$from_email_address. "\r\n"; 
					$headers2 .= "MIME-Version: 1.0\r\n";
					$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				
				wp_mail( $admin_email,$form_name.' New Submission Notification', $notification_message,$headers2 );//Sends email to user on successful registration
				 
				
		}
		
		public function crf_get_success_message($id)
		{
			$success_message = $this->crf_get_form_option_value('success_message',$id);
			
			if($success_message=="")
			{
				$success_message = __('Thank you for your submission.',$textdomain);
			}
			?>
			<div id="crf-form">
		  <div id="main-crf-form">
			<div class="main-edit-profile"><?php echo $success_message;?>
				<br />
			  <br />
			</div>
		  </div>
		</div>
			<?php	
		}
		
		
		public function crf_send_user_email($id,$post,$userid=0)
		{
				global $wpdb;
				$crf_fields=$wpdb->prefix."crf_fields";
			  //Fetches Auto Responder Subject from dashboard settings
			  if($userid!=0):
			  $user_email = $post['user_email'];
			  $user_name =  $post['user_name'];
			  $random_password =  $post['user_pass'];
			  endif;
			  $textdomain = 'custom-registration-form-pro-with-submission-manager';
			  $form_type = $this->crf_get_form_option_value('form_type',$id);
			  $sendpassword = $this->crf_get_global_option_value('send_password');
			  $subject = $this->crf_get_form_option_value('crf_welcome_email_subject',$id);
			  
			  $from_email_address = $this->crf_get_global_option_value('from_email');
			  if($from_email_address=="")
			  {
				  $from_email_address = get_option('admin_email');	
			  }
		
			  
			  if($subject == "")
			  {
				$subject = get_bloginfo('name');//Auto inserts email Subject if it is not defined in dashboard settings
			  }
			  //Fetches registration email body from dashboard settings
			  $message = $this->crf_get_form_option_value('crf_welcome_email_message',$id);
			  if($message == "" && $form_type=='reg_form')
			  {
				  $message = __('Thank you for registration.',$textdomain);//Auto inserts this text as email body if it is not defined in dashboard settings
			  }
			  
			  if($message == "" && $form_type=='contact_form')
			  {
				  $message = __('Thank you for your submission.',$textdomain);//Auto inserts this text as email body if it is not defined in dashboard settings
			  }
			  
			  if($form_type=='reg_form' && $sendpassword=='yes' && $userid!=0)//Inserts password into registration email body if auto-generation of password is enabled
			  {
				$message .="\r\n";  
				$message .= __('You can use following details for login.',$textdomain);
				$message .="\r\n";  
				$message .= __('Username : ',$textdomain).$user_name;
				$message .="\r\n";  
				$message .= __('Password : ',$textdomain).$random_password;
				$message .="\r\n";  
			  }
		
			  if($form_type=='contact_form')
			  {
				$qry1 = "select * from $crf_fields where Form_Id= '".$id."' and Type ='email' order by ordering asc limit 1";
				 $row1 = $wpdb->get_row($qry1);
				 if(isset($row1))
				 {
				 $emailfield = sanitize_key($row1->Name).'_'.$row1->Id;
				 $user_email =  $post[$emailfield]; 
				 }
			  }
			  
			  $headers = 'From:'.$from_email_address. "\r\n"; 
			  if(isset($user_email))
			  {
				wp_mail( $user_email, $subject, $message, $headers );//Sends email to user on successful registration
			  }
			  
				
		}
		
		public function crf_integrate_facebook_login()
		{
			  $facebook_login = $this->crf_get_global_option_value('enable_facebook');
			  if($facebook_login=='yes')
			  {
				  include '../facebook/crf_facebook.php';
				  upb_fb_login_validate();
				  upb_fb_loginForm();
			  }	
		}
		
		public function crf_insert_submission($submission_id,$form_id,$field,$value)
		{
			global $wpdb;
			if(is_array($value)){ print_r($value); die;}
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "insert into $crf_submissions values('','".$submission_id."','".$form_id."','".$field."','".$value."')";	
			$wpdb->query($qry);
		}
		
		public function crf_submision_field_value($submission_id,$field)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$qry = "select `value` from $crf_submissions where submission_id='".$submission_id."' and `field`='".$field."'";	
			$data = $wpdb->get_var($qry);
			return $data;
			
		}
		
		public function crf_get_submissions($entries,$form_id)
		{
			global $wpdb;
			$crf_submissions =$wpdb->prefix."crf_submissions";	
			$crf_fields =$wpdb->prefix."crf_fields";	
			$qry = "select * from $crf_fields where form_id ='".$form_id."' and Type not in('heading','paragraph','file') order by ordering asc limit 4";
			$reg = $wpdb->get_results($qry);
			?>
			<li class="header rows">
				<div class="cols" style="width:30px;"></div>
				<div class="cols" style="width:30px;">#</div>
				<?php
			foreach($reg as $row)
			{
				$key = $this->crf_get_field_key($row);
				?>
				<div class="cols" style="width:19%">
				  <?php 
				$fieldnamelength = strlen($row->Name);
				if($fieldnamelength<=15){echo $row->Name;}
				else
				{
				$fieldnamehalf = substr($row->Name, 0, 15);
				echo $fieldnamehalf.'...';
				}?>
				</div>
				<?php
			}
			?>
			</li>
			<?php
			
			$i=1;
			
			foreach($entries as $entry)
			{
				
				if($i%2==0)
				{
					$class="";
				}
				else
				{
					$class="alternate";
				} ?>
			
					<li class="<?php echo $class;?> rows">
				<div class="cols" style="width:30px;">
				  <input type="checkbox" name="selected[]" value="<?php echo $entry->submission_id; ?>" />
				</div>
				<div class="cols" style="width:30px;"><a href="admin.php?page=crf_view_entry&id=<?php echo $entry->submission_id;?>"><?php echo $i; ?></a></div>
				<?php foreach($reg as $row){?>
				  <div class="cols" style="width:19%">
					  <?php 
					  $key = $this->crf_get_field_key($row);
					  $result = $this->crf_submision_field_value($entry->submission_id,$key);
				  $Valuehalf = substr($result, 0, 15);
				  if(strlen($result) < 15)
				  {
				  echo $result;
				  }
				  else
				  {
					echo $Valuehalf.'...'; 
				  }
				  ?>
					</div>
					<?php } ?>
			
				  <div class="cols" style="width:50px"><a href="admin.php?page=crf_view_entry&id=<?php echo $entry->submission_id;?>">View</a></div>
		
			  </li>
			  <?php
			  $i++;
			}
			
			
		}
		
		public function crf_get_field_key($row)
		{
			$key = sanitize_key($row->Name).'_'.$row->Id;
			return $key;	
		}
		
		public function crf_get_pagination($num_of_pages,$pagenum)
		{
			$args = array(
			'base'               => add_query_arg( 'pagenum', '%#%' ),
			'format'             => '',
			'total'              => $num_of_pages,
			'current'            => $pagenum,
			'show_all'           => False,
			'end_size'           => 1,
			'mid_size'           => 2,
			'prev_next'          => True,
			'prev_text'          => __('&laquo;', 'text-domain' ),
			'next_text'          => __('&raquo;', 'text-domain'),
			'type'               => 'plain',
			'add_args'           => False,
			'add_fragment'       => '',
			'before_page_number' => '',
			'after_page_number'  => '' );
		
			$page_links = paginate_links( $args );
			if ( $page_links ) 
			{
				echo '<div class="tablenav crfpagination"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
			}
			
		}
		
		public function crf_get_all_form_list_option()
		{
			global $wpdb;
			$crf_forms =$wpdb->prefix."crf_forms";
			$qry = "select * from $crf_forms";
			$reg = $wpdb->get_results($qry);
			if(!empty($reg))
			{
				foreach($reg as $row)
				{
					?>
				<option value="<?php echo $row->id;?>" <?php if($_REQUEST['form_id']==$row->id) echo 'selected';?>>
				<?php 
				$formnamelength = strlen($row->form_name);
				if($formnamelength<=15){echo $row->form_name;}
				else
				{
				$formnamehalf = substr($row->form_name, 0, 15);
				echo $formnamehalf.'...';
				}?>
				</option>
				<?php
				}
			}	
		}

}

?>