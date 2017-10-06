<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_CONTRACTOR; require("authenticate.php");
	require('functions/events.php');
	
	$event_id = (int) $_GET['eid'];
	$event_info = getEvent($event_id);
	
	$success = false; // Boolean for successful event registration
	
	
	// Register for Event
	if(isset($_POST['submit'])){
		try {
			$required = array('first_name', 'last_name', 'email', 'address', 'city', 'state', 'zip', 'phone');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->isEmail('email');
			$validator->noFilter('address');
			$validator->noFilter('address_2');
			$validator->noFilter('city');
			$validator->noFilter('state');
			$validator->noFilter('zip');
			$validator->noFilter('phone');
			$validator->noFilter('comments');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				// Clean data for SQL
				$clean = array_map('escapeData', $filtered);
				
				$result = newEventRegistration($event_id, $_SESSION['user_info']['user_id'], $clean['first_name'], $clean['last_name'], $clean['email'], $clean['address'], $clean['address_2'], $clean['city'], $clean['state'], $clean['zip'], cleanPhone($clean['phone']), $clean['comments']);
				if($result > 0){
					$alerts->addAlert('You have been successfully registered for this event.');
					$success=true;
					
					
					require('classes/PHPMailer.php');
					
					// Send Email Notification to IW
					try {
						$mail = new PHPMailer();
		
						$mail->SetFrom('info@iwlocal3.com');
						//$mail->AddAddress('taylor@taylorcollinsdesign.com');
						$mail->AddAddress($event_info['email']);
						$mail->Subject = "Event Registration Notification";
						
						$html = '<b>Event Registration Notification</b><br>
<br>
Someone has just registered for the following event:<br>
<br>
<b>'.$event_info['event'].'</b><br>'.
date('F j, Y', strtotime($event_info['date'])).'<br>
<br>';

         	if($event_info['times']){

						$html .=
'<br>Time: '.$event_info['times'].'</span>';
	
         	}
   
         	if($event_info['location']){

						$html .=
'<br>Location: '.$event_info['location'].'</span>';
	
         	}
         
						$html .= '
<br>
<br>'.nl2br($event_info['description']);


						
						$mail->MsgHTML($html);
						
						$mail->Send();
						
					} catch (phpmailerException $e) {
					  //$alerts->addAlert($e->errorMessage());
					} catch (Exception $e) {
					  //$alerts->addAlert($e->getMessage());
					}
					
					
					
					// Send Email Notification to Registrant
					try {
						$mail = new PHPMailer();
		
						$mail->SetFrom('info@iwlocal3.com');
						//$mail->AddAddress('taylor@taylorcollinsdesign.com');
						$mail->AddAddress($filtered['email']);
						$mail->Subject = "Event Registration Confirmation";
						
						$html = '<b>Event Registration Confirmation</b><br>
<br>
You have successfully registered for the following event:<br>
<br>
<b>'.$event_info['event'].'</b><br>'.
date('F j, Y', strtotime($event_info['date'])).'<br>
<br>';

         	if($event_info['times']){

						$html .=
'<br>Time: '.$event_info['times'].'</span>';
	
         	}
   
         	if($event_info['location']){

						$html .=
'<br>Location: '.$event_info['location'].'</span>';
	
         	}
         
						$html .= '
<br>
<br>'.nl2br($event_info['description']);
						
						$mail->MsgHTML($html);
						
						$mail->Send();
						
					} catch (phpmailerException $e) {
					  //$alerts->addAlert($e->errorMessage());
					} catch (Exception $e) {
					  //$alerts->addAlert($e->getMessage());
					}
					
					
					
				} else {
					$alerts->addAlert('You could not be registered for this event.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch (Exception $e){
			$alert->addAlert('An unknown error occured.');
			//$alert->addAlert($e);
		}
	}
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title><?php echo $event_info['event']; ?> | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/events.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='events'; include("subnav_contractor.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<?php include('common/events_register.php'); ?>
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>