<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/events.php');
	require('functions/payments.php');
	
	$registration_id = (int) $_GET['rid'];
	$registration_info = getEventRegistration($registration_id);
	
	$event_info = getEvent($registration_info['event_id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: View Event Registration | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='events'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="events.php">Events</a></li>
         	<li><a href="events_registrations.php?eid=<?php echo $registration_info['event_id']; ?>">Event Registrations</a></li>
         	<li>View Event Registration</li>
         </ul>
               
      	<h1>Admin: View Event Registration</h1>
         <p style="padding-bottom:15px;margin-bottom:0;border-bottom:1px solid #ddd;">
				<strong><?php echo $event_info['event']; ?></strong><br />
         	<span style="font-size:.9em;"><?php echo date('F j, Y', strtotime($event_info['date'])); ?></span>
         </p>
         
         
         <div style="width:300px;margin-right:50px;padding-top:20px;">
         
            <p>
               Registered on <?php echo date('F j, Y', strtotime($registration_info['date'])); ?><br />
               <br />
               <?php echo $registration_info['first_name'].' '.$registration_info['last_name']; ?><br />
               <?php echo $registration_info['address']; ?><br />
               <?php echo ($registration_info['address_2']) ? $registration_info['address_2'].'<br />' : ''; ?>
               <?php echo $registration_info['city'].', '.$registration_info['state'].' '.$registration_info['zip']; ?><br />
               <br />
               <?php echo formatPhone($registration_info['phone']); ?><br />
               <a href="mailto:<?php echo $registration_info['email']; ?>"><?php echo $registration_info['email']; ?></a>
            </p>
         
<?php
		if($registration_info['comments']){
?>
            <strong>Comments</strong><br />
            <?php echo nl2br($registration_info['comments']); ?>
<?php
		}
?>
			</div>
         <div style="width:300px;padding-top:66px;">

<?php
		if($registration_info['payment_id']){
			$payment_info = getPayment($registration_info['payment_id']);
?>
            <strong>Payment Info</strong><br />
            <?php echo $payment_info['first_name'].' '.$payment_info['last_name']; ?><br />
            <?php echo formatPhone($payment_info['phone']); ?><br />
            <a href="mailto:<?php echo $payment_info['email']; ?>"><?php echo $payment_info['email']; ?></a><br /><br />
            
             $<?php echo number_format($payment_info['amount'], 2); ?> on <?php echo date('F j, Y', strtotime($payment_info['date'])); ?><br />
            <?php echo ucwords($payment_info['card_type']); ?> ending <?php echo $payment_info['card_number']; ?>
<?php
		}
?>
				</div>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>