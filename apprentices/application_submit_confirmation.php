<?php
	require('config.php');
	require('functions/payments.php');
	require('functions/apprenticeships.php');
	
	
	// Make sure that the payment session exists
	if(empty($_SESSION['payment'])){
		header('Location: application_submit.php');
		exit();
	}
	
	// Put payment data in array and destroy payment session
	$payment_info = $_SESSION['payment'];
	$_SESSION['payment'] = NULL;
	unset($_SESSION['payment']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Apprenticeship Application Form | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
      	<h1>Apprenticeship Application Form</h1>
                  
         <div class="main_left">
         	
            <p style="font-weight:bold;" class="highlight">Your application and payment have been submitted successfully. Please <a href="javascript:print();">print this page</a> for your records.</p>
            
            <h2>Payment Info</h2>
            <?php echo date('F j, Y', $payment_info['timestamp']); ?><br />
           	<br />
            <?php echo $payment_info['first_name']." ".$payment_info['last_name']; ?><br />
            <?php echo formatPhone($payment_info['phone']); ?><br />
            <?php echo $payment_info['email']; ?><br />
            <br />
            <strong>Amount Paid:</strong> $<?php echo number_format($payment_info['amount'], 2); ?><br />
            <strong>Transaction ID:</strong> <?php echo $payment_info['transaction_id']; ?>
            
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