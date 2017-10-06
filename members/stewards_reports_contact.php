<?php
	require('config.php');
	require('functions/stewards_reports.php');
	
	
	// Steward's Report ID	
	$report_id = (!empty($_GET['rid'])) ? (int) $_GET['rid'] : false;
	
	
	// Send Emails to IW
	if(isset($_POST['submit'])){
		try {
			$required = array('first_name', 'last_name', 'phone');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('phone');
			$validator->isEmail('email');
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				require('classes/PHPMailer.php');
			
				try {
					$mail = new PHPMailer();
	
					$mail->SetFrom('info@iwlocal3.com');
					//$mail->AddAddress('taylor@taylorcollinsdesign.com');
					$mail->AddAddress('info@iwlocal3.com');
					$mail->Subject = "Steward's Report Correction Request";
					
					$html = "<b>Steward's Report Correction Request</b><br>
<br>";

					if($report_id){
						$report_info = getStewardsReport($report_id);
						if($report_info > 0){
						
						$html .= "<b>Report IDs:</b> ".$report_info['report_id']." / ".$report_info['id']."<br>
<br>";
						
						}
					}

					foreach($_POST as $name=>$value){
						if(!empty($value) && $value != 'Please specify'){
							if($name != 'submit'){
								$html .= '<b>'.ucwords(str_replace('_',' ',$name)).':</b> '.nl2br($value).'<br><br>
								
';
							}
						}
					}
					
					$mail->MsgHTML($html);
					$mail->Send();
					
					$alerts->addAlert('Thank you. Your information was sent successfully.' ,'success');
					unset($_POST);
					
				} catch (phpmailerException $e) {
				  $alerts->addAlert($e->errorMessage());
				} catch (Exception $e) {
				  $alerts->addAlert($e->getMessage());
				}

				
			} else {
				$alerts->addAlerts($errors);
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
         
            <p>If you made a mistake while completing a Steward's Report, please complete and submit the form below.</p>
                        
            <?php if($alerts->hasAlerts()) echo $alerts; ?>
   
            <br style="clear:left;" />
            
            <form action="<?php echo getSelf(); ?>?rid=<?php echo $report_id; ?>" method="post">
               <p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($_POST['first_name'], ENT_QUOTES); ?>" /></p>
               <p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($_POST['last_name'], ENT_QUOTES); ?>" /></p>
               <p><label for="phone"><span class="required">*</span> Phone</label> <input type="text" name="phone" id="phone" value="<?php echo htmlentities($_POST['phone'], ENT_QUOTES); ?>" /></p>
               <p><label for="email">Email</label> <input type="text" name="email" id="email" value="<?php echo htmlentities($_POST['email'], ENT_QUOTES); ?>" /></p>
                                            
               <p style="margin:30px 0 15px 90px;">
               	<label for="comments" class="normal">Describe the mistake(s) in detail.</label>
               	<textarea name="comments" id="comments" cols="40" rows="10" style="width: 100%;"><?php echo $_POST['comments']; ?></textarea>
               </p>
               
               <p class="submit"><input type="submit" name="submit" id="submit" value="Submit" /></p>
            </form>
                        
         </div><!-- div.main_left -->
         <div class="main_right">
         	
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>