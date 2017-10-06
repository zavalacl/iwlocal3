<?php
	require('config.php');
	
	// Submit Application
	if(isset($_POST['submit'])){
		try {
			$required = array('first_name', 'last_name', 'address', 'city', 'state', 'zip', 'email', 'phone', 'card_type', 'card_number', 'exp_month', 'exp_year', 'ccv', 'file');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('address');
			$validator->noFilter('city');
			$validator->noFilter('state');
			$validator->noFilter('zip');
			$validator->isEmail('email');
			$validator->noFilter('phone');
			$validator->noFilter('card_type');
			$validator->noFilter('card_number');
			$validator->isInt('exp_month', 1, 12);
			$validator->isInt('exp_year', date('Y'));
			$validator->isInt('ccv');
			$validator->isValidDocument('file', array('pdf'));
			$validator->noFilter('comments');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			$error_fields = $validator->getErrorFields();
			
			if(!$errors){
				
				// Upload Application File
				$extension = getExtension($_FILES['file']['name']);
				$filename = uniqid(time()).'.'.$extension;
				
				if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['apprenticeship_applications'].$filename)){
				
					// Save form data to session
					$_SESSION['payment'] = array();
					$_SESSION['payment'] = $_POST;
					$_SESSION['payment']['original_filename'] = $_FILES['file']['name'];
					$_SESSION['payment']['filename'] = $filename;
					
					// Redirect to confirm page
					header('Location: application_submit_confirm.php');
					exit();
					
				} else {
					$alerts->addAlert('Your application could not be uploaded. Please try again.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch (Exception $e){
			$alert->addAlert('An unknown error occured.');
			//$alert->addAlert($e);
		}
	}
	
	
	// Get Form Data from POST if it exists, otherwise try the payment session
	$form_data = array();
	if(isset($_POST['submit'])){
		$form_data = $_POST;
	} else {
		$form_data = $_SESSION['payment'];
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Submit Your Apprenticeship Application Form | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='apprentices'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='application'; include("subnav_apprentices.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Submit Your Apprenticeship Application Form</h1>
                  
         <div class="main_left">
         	<p>Complete the form below to submit your application and non-refundable $<?php echo number_format(APPRENTICESHIP_APPLICATION_FEE, 2); ?> administrative fee.</p>
            
         	<?php if($alerts->hasAlerts()) echo $alerts; ?>
         	
            <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data" style="margin-top:30px;">
            	<p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($form_data['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($form_data['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><label for="address"><span class="required">*</span> Billing Address</label> <input type="text" name="address" id="address" value="<?php echo htmlentities($form_data['address'], ENT_QUOTES, 'UTF-8'); ?>" /></p>
               <p><label for="city"><span class="required">*</span> City</label> <input type="text" name="city" id="city" value="<?php echo htmlentities($form_data['city'], ENT_QUOTES, 'UTF-8'); ?>" /></p>
               <p>
                  <label for="state"><span class="required">*</span> State</label>
                  <select name="state" id="state" style="width:auto;">
                     <option value=""></option>
<?php
                  foreach($state_list as $abbrev=>$state){
?>
                     <option value="<?php echo $abbrev; ?>"<?php echo ($form_data['state']==$abbrev) ? ' selected="selected"' : ''; ?>><?php echo $state; ?></option>
<?php
                  }
?>
                  </select>
               </p>
               <p><label for="zip"><span class="required">*</span> Zip</label> <input type="text" name="zip" id="zip" style="width:80px;" value="<?php echo htmlentities($form_data['zip'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><label for="email"><span class="required">*</span> Email</label> <input type="text" name="email" id="email" value="<?php echo htmlentities($form_data['email'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><label for="phone"><span class="required">*</span> Phone</label> <input type="text" name="phone" id="phone" value="<?php echo htmlentities($form_data['phone'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               
               <p style="padding-top:20px;">
               	<label for="card_type"><span class="required">*</span> Card Type</label> 
               	<select name="card_type" id="card_type" style="width:auto;">
                  	<option value=""></option>
                     <option value="Visa"<?php echo ($form_data['card_type'] == 'Visa') ? ' selected="selected"' : ''; ?>>Visa</option>
                     <option value="Mastercard"<?php echo ($form_data['card_type'] == 'Mastercard') ? ' selected="selected"' : ''; ?>>Mastercard</option>
                     <option value="American Express"<?php echo ($form_data['card_type'] == 'American Express') ? ' selected="selected"' : ''; ?>>American Express</option>
                  </select>   
               </p>
               <p><label for="card_number"><span class="required">*</span> Card Number</label> <input type="text" name="card_number" id="card_number" value="<?php echo htmlentities($form_data['card_number'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><div class="label"><span class="required">*</span> Expiration</div> 
               	<select name="exp_month" id="exp_month" style="width:auto;">
                  	<option value="">Month</option>
<?php
						for($i=1; $i<=12; $i++){
?>
							<option value="<?php echo $i; ?>"<?php echo ($form_data['exp_month']==$i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
<?php
						}
?>
						</select>
             		<select name="exp_year" id="exp_year" style="width:auto;">
                  	<option value="">Year</option>
<?php
                  for($j=date('Y'); $j<=(date('Y')+10); $j++){
?>
                  	<option value="<?php echo $j; ?>"<?php echo ($form_data['exp_year']==$j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php
                  }
?>
               	</select>
               </p>
               <p><label for="ccv"><span class="required">*</span> CCV/CVC</label> <input type="password" name="ccv" id="ccv" style="width:80px;" value="" /></p>
               
               <p style="padding-top:20px;"><label for="file"><span class="required">*</span> Application (PDF)</label> <input type="file" name="file" id="file" /></p>
               <p><label for="comments">Comments</label> <textarea name="comments" id="comments" cols="40" rows="5"><?php echo htmlentities($form_data['comments'], ENT_QUOTES, 'utf-8'); ?></textarea></p>
               
               <p class="submit">
               	<input type="submit" name="submit" id="submit" value="Continue" style="float:left;" />
                  <div style="width:200px;padding-left:10px;line-height:1.1em;" class="note highlight">Your credit card will not be charged until after the next step.</div>
               </p>
            </form>
            
         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/application_form.jpg" alt="" class="shadow" />
            
            <div class="box">
            	<span class="title">Project Gallery</span><br />
               Click here to view highlights of the exhibition of our work.<br />
               <a href="/our_work/" class="learn_more">View now</a>
            </div><!-- div.box -->
            <div class="box">
            	<span class="title">Tell a Friend</span><br />
               Click here to send this information and website to a friend.<br />
               <a href="tell_friend.php" class="learn_more">Send now</a>
            </div><!-- div.box -->
            
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>