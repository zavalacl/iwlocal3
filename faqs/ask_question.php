<?php
	require('config.php');
	
	// Send Emails
	if(isset($_POST['submit'])){
		try {
			$required = array('name', 'email', 'question');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->isEmail('email');
			$validator->noFilter('question');
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
					
				require('classes/PHPMailer.php');
			
				try {
					$mail = new PHPMailer();
	
					$mail->SetFrom('info@iwlocal3.com');
					//$mail->AddAddress('taylor@taylorcollinsdesign.com');
					$mail->AddAddress('info@iwlocal3.com');
					$mail->Subject = "Question From Website";
					
					$html = "<b>Question From the Website FAQ Form</b><br>
<br>
";

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
					$alerts->addAlert("Your information was sent successfully.");
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
<title>FAQ | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='ask_question'; include("subnav_faqs.php"); ?>
      </div><!-- div.left -->
      <div class="right">
         <h1>Ask a Question</h1>
         
         <p>Use the form below to submit a question to Iron Workers Local Union No. 3</p>

			<?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>" method="post">
         	<p><label for="name"><span class="required">*</span> Name</label> <input type="text" name="name" id="name" value="<?php echo stripslashes($_POST['name']); ?>" /></p>
            <p><label for="email"><span class="required">*</span> Email</label> <input type="text" name="email" id="email" value="<?php echo stripslashes($_POST['email']); ?>" /></p>
            <p><label for="question"><span class="required">*</span> Question</label> <textarea name="question" id="question" cols="40" rows="5"><?php echo stripslashes($_POST['question']) ; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Submit Question" /></p>
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>