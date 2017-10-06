<?php
	require('config.php');
	require('functions/payments.php');
	require('functions/apprenticeships.php');
	
	
	// Make sure that the payment session exists
	if(empty($_SESSION['payment'])){
		header('Location: application_submit.php');
		exit();
	}
	
	
	
	// Submit Application and Payment
	if(isset($_POST['submit'])){
		
		// Clean data for SQL
		$clean = array_map('escapeData', $_SESSION['payment']);
		
		
		// Save Payment
		$amount = $_SESSION['payment']['amount'] = APPRENTICESHIP_APPLICATION_FEE;
		
		$payment_id = newPayment(false, false, PAYMENT_TYPE_APPLICATION_FEE, $clean['first_name'], $clean['last_name'], $clean['email'], cleanPhone($clean['phone']), $clean['card_type'], substr($clean['card_number'], -4), $amount);
		if($payment_id > 0){
			
			// Process CC Transaction
			define('PROCESS_PAYMENT', true);
			if(true){
				$_SESSION['payment']['transaction_id'] = $transaction_id = "TEST_ID";
				$_SESSION['payment']['timestamp'] = time();
				
				
				// Save Transaction ID from Payment Gateway
				setPaymentTransactionId($payment_id, $transaction_id);

					
				// Save Application
				$hash = md5(uniqid(time()));
				$application_id = newApprenticeshipApplication($_SESSION['payment']['filename'], escapeData($_SESSION['payment']['original_filename']), $hash);
				
				
				// Email Application
				require('classes/PHPMailer.php');
				
				try {
					$mail = new PHPMailer();
	
					$mail->SetFrom('info@iwlocal3.com');
					$mail->AddAddress('taylor@taylorcollinsdesign.com');
					//$mail->AddAddress('info@iwlocal3.com');
					$mail->Subject = "Apprenticeship Application";
					
					$html = "<h3>Apprenticeship Application</h3>";
			
				// If application file was successfully saved to the database, provide download link.
				// Otherwise, attach it to the email.
				if($application_id > 0){
					$html .=
"<b>Application PDF</b><br>
".WEB_ADDRESS."/download.php?t=apprenticeship_applications&id=$application_id&h=$hash
<br><br>";
				} else {
					$mail->AddAttachment($file_paths['apprenticeship_applications'].$_SESSION['payment']['filename']);
				}
			
					$html .=
"<b>Payment Info</b><br>
<b>Date:</b> ".date('F j, Y \a\t g:ia', $_SESSION['payment']['timestamp'])."<br>
<br>
".$_SESSION['payment']['first_name']." ".$_SESSION['payment']['last_name']."<br>
".formatPhone($_SESSION['payment']['phone'])."<br>
".$_SESSION['payment']['email']."<br>
<br>
<b>Amount Paid:</b> $".number_format($amount, 2)."<br>
<b>Transaction ID:</b> ".$transaction_id;


					$mail->MsgHTML($html);
					
					$mail->Send();
					
					header('Location: application_submit_confirmation.php?t='.$transaction_id);
					exit();
					
				} catch (phpmailerException $e) {
				  //$alerts->addAlert($e->errorMessage());
				  $alerts->addAlert('Your payment was successfully processed, however, your application could not be sent. Please contact us immediately and reference this transaction ID: '.$transaction_id);
				} catch (Exception $e) {
				  //$alerts->addAlert($e->getMessage());
					$alerts->addAlert('Your payment was successfully processed, however, your application could not be sent. Please contact us immediately and reference this transaction ID: '.$transaction_id);
				}
				
			} else {
				deletePayment($payment_id);
				$alerts->addAlert('Your payment could not be processed. Please check your payment information and try again.');
			}
		} else {
			$alerts->addAlert('There was an error processing your payment. Please try again.');
		}
			
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
         <h2>Confirm Your Information</h2>
                  
         <div class="main_left">
         	<p>Confirm that the information below is correct, and then click the "Submit Application" button.</p>
            
         	<?php if($alerts->hasAlerts()) echo $alerts; ?>
            
            <form action="<?php echo getSelf(); ?>" method="post">
         	
               <p style="margin-top:20px;"><strong>Amount Being Charged:</strong> $<?php echo number_format(APPRENTICESHIP_APPLICATION_FEE, 2); ?></p>
               
               <p><?php echo $_SESSION['payment']['first_name'].' '.$_SESSION['payment']['last_name']; ?><br />
               <?php echo $_SESSION['payment']['address']; ?><br />
					<?php echo $_SESSION['payment']['city']; ?>, <?php echo $_SESSION['payment']['state']; ?> <?php echo $_SESSION['payment']['zip']; ?><br />
               <?php echo $_SESSION['payment']['email']; ?><br />
               <?php echo formatPhone($_SESSION['payment']['phone']); ?><br />
               <br />
               <strong>Application File:</strong> <?php echo $_SESSION['payment']['original_filename']; ?></p>
               
               <p><?php echo $_SESSION['payment']['card_type']; ?> ending <?php echo substr($_SESSION['payment']['card_number'], -4); ?><br />
               Expires: <?php echo $_SESSION['payment']['exp_month']; ?>/<?php echo $_SESSION['payment']['exp_year']; ?></p>
            
<?php
				if($_SESSION['payment']['comments']){
?>
					<p><strong>Comments:</strong><br /><?php echo nl2br($_SESSION['payment']['comments']); ?></p>
<?php
				}
?>

               <button type="submit" name="submit" id="submit">Submit Application</button> 
               <button type="button" onclick="window.location='application_submit.php'">Go Back</button>
            
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