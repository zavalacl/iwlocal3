<?php
	require('config.php');
	require('recaptchalib.php');
	
	
	// Send Emails
	if(isset($_POST['submit'])){
		try {
			$required = array('first_name', 'last_name', 'email');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->isEmail('email');
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			// Captcha
			$resp = recaptcha_check_answer(RECAPTCHA_PRIVATE_KEY,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

 			if(!$resp->is_valid){
 				$errors[] = 'The check words you entered were incorrect.';
 			}
			
			if(!$errors){
					
				require('classes/PHPMailer.php');
			
				try {
					$mail = new PHPMailer();
	
					$mail->SetFrom('info@iwlocal3.com');
					//$mail->AddAddress('taylor@taylorcollinsdesign.com');
					$mail->AddAddress('info@iwlocal3.com');
					$mail->Subject = "Contact From Website";
					
					$html = "<b>Contact From Website</b><br>
<br>";

					foreach($_POST as $name=>$value){
						if(!empty($value) && $value != 'Please specify'){
							if($name != 'submit' && $name != 'heard_by' && stripos($name, 'Recaptcha')===false){
								$html .= '<b>'.ucwords(str_replace('_',' ',$name)).':</b> '.$value.'<br><br>
								
';
							} else if($name == 'heard_by'){
								$html .= '<b>Heard By:</b> '.implode(', ', $value).'<br><br>
								
';
							}
						}
					}
					
					$mail->MsgHTML($html);
					
					$mail->Send();
					$alerts->addAlert('Thank you. Your information was sent successfully.', 'success');
					unset($_POST);
					
				} catch (phpmailerException $e) {
				  $alerts->addAlert($e->errorMessage(), 'error');
				} catch (Exception $e) {
				  $alerts->addAlert($e->getMessage(), 'error');
				}

				
			} else {
				$alerts->addAlerts($errors, 'error');
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again.', 'error');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Contact Us | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<script type="text/javascript">
var RecaptchaOptions = {
   theme : 'white'
};
</script>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	
      </div><!-- div.left -->
      <div class="right">
      	<h1>Contact Us</h1>
         
         <div class="main_left">
         
            <p>Thank you for your interest in the Iron Workers Local Union No. 3. If you would like to learn more about us please take a few minutes to complete the fields below and we will contact you with more information.</p>
            
            <p><strong>Iron Workers Local Union No.3 Union Hall</strong><br />
            2201 Liberty Avenue<br />
            Pittsburgh, PA 15222<br />
            PH: (412) 227-6767 or TOLL FREE: (800) 927-3198<br />
            FX: (412) 261-3536</p>
            
            <p style="margin-top:20px;"><strong>The Iron Workers Local Union No. 3 Training Center</strong><br />
            2315 Liberty Ave.<br />
            Pittsburgh, PA 15222<br />
            PH: (412) 471-4535 or TOLL FREE: (877) 771-4535<br />
            FX: (412) 471-4536</p>
            
            <p style="margin-top:20px;"><strong>Erie Office</strong><br />
            4901 East Lake Road<br />
            Erie, PA 16511<br />
            PH: (814) 898-2060<br />
            FX: (814) 898-2061</p>
            
            <p style="margin-top:20px;"><strong>Clearfield Office</strong><br />
            1402 Leonard Street<br />
            P.O. Box 1472<br />
            Clearfield, PA 16830<br />
            PH: (814) 765-7535<br />
            FX: (814) 765-7590</p>
            
            <?php if($alerts->hasAlerts()) echo $alerts; ?>
   
            <br style="clear:left;" />
            
            <form action="<?php echo getSelf(); ?>" method="post">
            
               <h3 style="margin-top:15px;">Contact Information</h3>
               <p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo stripslashes($_POST['first_name']); ?>" /></p>
               <p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo stripslashes($_POST['last_name']); ?>" /></p>
               
               
               <h3>Home Address</h3>
               <p><label for="address">Address</label> <input type="text" name="address" id="address" value="<?php echo stripslashes($_POST['address']); ?>" /></p>
               <p><label for="city">City</label> <input type="text" name="city" id="city" value="<?php echo stripslashes($_POST['city']); ?>" /></p>
               <p><label for="state">State</label> <input type="text" name="state" id="state" value="<?php echo stripslashes($_POST['state']); ?>" /></p>
               <p><label for="zip">Zip</label> <input type="text" name="zip" id="zip" value="<?php echo stripslashes($_POST['zip']); ?>" /></p>
               
               <h3>Business Address</h3>
               <p><label for="business_address">Address</label> <input type="text" name="business_address" id="business_address" value="<?php echo stripslashes($_POST['business_address']); ?>" /></p>
               <p><label for="business_city">City</label> <input type="text" name="business_city" id="business_city" value="<?php echo stripslashes($_POST['business_city']); ?>" /></p>
               <p><label for="business_state">State</label> <input type="text" name="business_state" id="business_state" value="<?php echo stripslashes($_POST['business_state']); ?>" /></p>
               <p><label for="business_zip">Zip</label> <input type="text" name="business_zip" id="business_zip" value="<?php echo stripslashes($_POST['business_zip']); ?>" /></p>
               
               <p style="margin-top:40px;"><label for="email"><span class="required">*</span> Email</label> <input type="text" name="email" id="email" value="<?php echo stripslashes($_POST['email']); ?>" /></p>
               <p><label for="phone">Phone</label> <input type="text" name="phone" id="phone" value="<?php echo stripslashes($_POST['phone']); ?>" /></p>
               <p><label for="mobile">Mobile</label> <input type="text" name="mobile" id="mobile" value="<?php echo stripslashes($_POST['mobile']); ?>" /></p>
               <div class="p">
                  <div class="label">&nbsp;</div> 
                  <div>
                  <span class="label">Preferred Method of Contact</span><br />
                  <input type="radio" name="preferred_method_of_contact" id="pmc_email" value="Email"<?php echo (empty($_POST['preferred_method_of_contact']) || $_POST['preferred_method_of_contact']=='Email') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="pmc_email" class="plain">Email</label>
                  <input type="radio" name="preferred_method_of_contact" id="pmc_phone" value="Phone"<?php echo ($_POST['preferred_method_of_contact']=='Phone') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="pmc_phone" class="plain">Phone</label>
                  </div>
               </div>
               
               <p style="margin-top:40px;"><label for="comments">Comments/<br />Questions</label> <textarea name="comments" id="comments" cols="40" rows="5"><?php echo stripslashes($_POST['comments']) ; ?></textarea></p>
               <div class="p">
                  <div class="label">&nbsp;</div>
                  <div>
                     <span class="label">Are you a...</span><br />
   <?php
                  $professions = array('Contractor','Owner','Member','Boomer','Apprentice');
                  foreach($professions as $profession){
   ?>
                     <input type="radio" name="profession" id="profession_<?php echo str_replace(' ','_',$profession); ?>" value="<?php echo $profession; ?>"<?php echo ($_POST['profession']==$profession) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="profession_<?php echo str_replace(' ','_',$profession); ?>" class="plain"><?php echo $profession; ?></label><br />
   <?php
                  }
   ?>
                     <input type="radio" name="profession" id="profession_other" value="Other"<?php echo ($_POST['profession']=='Other') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="profession_other" class="plain">Other</label> <input type="text" name="profession_specify" id="profession_specify" value="<?php echo (!empty($_POST['profession_specify'])) ? stripslashes($_POST['profession_specify']) : 'Please specify'; ?>" onclick="checkInput(this,'Please specify')" onblur="checkInput(this,'Please specify')" style="font-size:.9em;" />
                  </div>
               </div>
               <div class="p">
                  <div class="label">&nbsp;</div>
                  <div>
                     <span class="label">How did you hear about us? (check all that apply)</span><br />
   <?php
                  $hear_by_types = array('Newspaper','Newsletter','TV','Radio','Pittsburgh Penguins Game',
                                         'Pittsburgh Penguins Website','Pittsburgh Steelers Game',
                                         'Pittsburgh Steelers Website','Washington Wild Things Game','Referral','Internet');
                  foreach($hear_by_types as $hear_by_type){
   ?>
                     <input type="checkbox" name="heard_by[]" id="heard_by_<?php echo str_replace(' ','_',$hear_by_type); ?>" value="<?php echo $hear_by_type; ?>"<?php echo (!empty($_POST['heard_by']) && @in_array($hear_by_type, $_POST['heard_by'])) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="heard_by_<?php echo str_replace(' ','_',$hear_by_type); ?>" class="plain"><?php echo $hear_by_type; ?></label><br />
   <?php
                  }
   ?>
                     <input type="checkbox" name="heard_by[]" id="heard_by_other" value="Other"<?php echo (!empty($_POST['heard_by']) && @in_array('Other', $_POST['heard_by'])) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="heard_by_other" class="plain">Other</label> <input type="text" name="heard_by_specify" id="heard_by_specify" value="<?php echo (!empty($_POST['heard_by_specify'])) ? stripslashes($_POST['heard_by_specify']) : 'Please specify'; ?>" onclick="checkInput(this,'Please specify')" onblur="checkInput(this,'Please specify')" style="font-size:.9em;" />
                  </div>
               </div>
               
               <div class="p">
               		<div class="label">&nbsp;</div>
               		<?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?>
               </div>
               
               <p class="submit"><input type="submit" name="submit" id="submit" value="Submit" /></p>
            </form>
            
            <p style="margin-top:30px;">No personal information will be shared with any person/persons or company/companies outside of Iron Workers Local Union No. 3 without your permission.</p>
            
         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/union_hall.jpg" alt="" class="shadow" /><br />
            <span class="note">Union Hall</span>
            <br /><br />
            <img src="/img/inner/apprenticeship_facility.jpg" alt="" class="shadow" /><br />
            <span class="note">Apprenticeship Facility</span>
            <br /><br />
            <img src="/img/inner/erie_office.jpg" alt="" class="shadow" /><br />
            <span class="note">Erie Office</span>
            <br /><br />
            <img src="/img/inner/clearfield_office.jpg" alt="" class="shadow" /><br />
            <span class="note">Clearfield Office</span>
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>