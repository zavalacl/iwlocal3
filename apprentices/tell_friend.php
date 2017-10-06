<?php
	require('config.php');
	require('recaptchalib.php');
	
	// Send Emails
	if(isset($_POST['submit'])){
		try {
			$required = array('your_name', 'name', 'email');
			
			$validator = new Validator($required);
			$validator->noFilter('your_name');
			$validator->noFilter('name');
			$validator->isEmail('email');
			$validator->noFilter('message');
			
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
	
					$mail->SetFrom('info@iwlocal3.com', $filtered['your_name']);
					$mail->AddAddress($filtered['email'], $filtered['name']);
					$mail->Subject = "{$filtered['your_name']} thinks you might be interested in the Iron Workers Local Union No. 3 Apprenticeship Program";
					
					$html = "Dear {$filtered['name']},<br>
<br>
Your friend, <b>{$filtered['your_name']}</b>, thought you might be interested in the Iron Workers Local Union No. 3 Apprenticeship Program. Follow the link below to find out more about our Apprenticeship Program.<br>
<br>
<a href='".WEB_ADDRESS."/apprentices/'>".WEB_ADDRESS."/apprentices/</a>";
				
				if(!empty($_POST['message'])){
					$message = htmlentities($filtered['message'], ENT_QUOTES);
					$html .= '
<br>
<br>'.nl2br($message);
					}
					
					$mail->MsgHTML($html);
					
					$mail->Send();
					$alerts->addAlert("The email was successfully sent.");
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
			$alerts->addAlert('There was an unknown error. Please try again.');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Apprentices | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
	<?php $nav_page='apprentices'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='tell_friend'; include("subnav_apprentices.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Tell a Friend</h1>
         
         <div class="main_left">
                     
            <p>Iron working is a demanding &amp; rewarding trade.  Ask a veteran iron worker about their work experience and they will proudly list the buildings in Western Pennsylvania that they have worked on from the ground up, along with the men and women who worked beside them. The camaraderie is unmistakable.</p>
            
				<p>Does iron working interest you? Do you know someone who would enjoy working outside, climbing steel, changing the skyline? Refer a friend:</p>
            
            <?php if($alerts->hasAlerts()) echo $alerts; ?>

            <br style="clear:left;" />
         
            <form action="<?php echo getSelf(); ?>" method="post">
               <p><label for="your_name"><span class="required">*</span> Your Name</label> <input type="text" name="your_name" id="your_name" value="<?php echo htmlentities($_POST['your_name'], ENT_QUOTES); ?>" /></p>
               <p><label for="name"><span class="required">*</span> Friend's Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($_POST['name'], ENT_QUOTES); ?>" /></p>
               <p><label for="email"><span class="required">*</span> Friend's Email</label> <input type="text" name="email" id="email" value="<?php echo htmlentities($_POST['email'], ENT_QUOTES); ?>" /></p>
               <p><label for="message">Optional Message</label> <textarea name="message" id="message" cols="40" rows="5"><?php echo htmlentities($_POST['message']); ?></textarea></p>
               
               <div class="p">
               		<?php echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY); ?>
               </div>
               
               <p class="submit"><input type="submit" name="submit" id="submit" value="Send Email" /></p>
            </form>

         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/tell_friend.jpg" alt="" class="shadow" />
            
            <div class="box">
            	<span class="title">Application Form</span><br />
               Click here to download an application form.<br />
               <a href="application.php" class="learn_more">Download now</a>
            </div><!-- div.box -->
            <div class="box">
            	<span class="title">Project Gallery</span><br />
               Click here to view highlights of the exhibition of our work.<br />
               <a href="/our_work/" class="learn_more">View now</a>
            </div><!-- div.box -->
            
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>