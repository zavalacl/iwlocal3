<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/events.php');
	
	
	// Add Event
	if(isset($_POST['submit'])){
		try {
			$required = array('month', 'day', 'year', 'event', 'email');
			
			$validator = new Validator($required);
			$validator->isInt('month');
			$validator->isInt('day');
			$validator->isInt('year');
			$validator->noFilter('event');
			$validator->isEmail('email');
			
			$validator->noFilter('times');
			$validator->noFilter('location');
			$validator->noFilter('description');
			$validator->isFloat('price');
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				$date = $clean['year'].'-'.$clean['month'].'-'.$clean['day'];
				$registration = (isset($_POST['registration'])) ? 1 : 0;
				
				if(newEvent($clean['event'], $date, $clean['email'], $clean['description'], $clean['times'], $clean['location'], $registration, $clean['price']) > 0){
					header("Location: events.php?ne");
					exit;
				} else {
					$alerts->addAlert('The event could not be added.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Events | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
         	<li>Add Event</li>
         </ul>
               
      	<h1>Admin: Add Event</h1>
         
         <p>Complete the form below to create a new event.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post">
         	<p><div class="label"><span class="required">*</span> Date</div>
            	<select name="month" id="month" style="width:auto;">
<?php
			for($i=1; $i<=12; $i++){
?>
					<option value="<?php echo $i; ?>"<?php echo ((!empty($_POST['month']) && $_POST['month']==$i) || (empty($_POST['month']) && $i==date('n'))) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php
			}
?>
               </select> 
               <select name="day" id="day" style="width:auto;">
<?php
			for($j=1; $j<=31; $j++){
?>
					<option value="<?php echo $j; ?>"<?php echo ((!empty($_POST['day']) && $_POST['day']==$j) || (empty($_POST['day']) && $j==date('j'))) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php
			}
?>
               </select> 
               <input type="text" name="year" id="year" value="<?php echo (!empty($_POST['year'])) ? $_POST['year'] : date('Y'); ?>" style="width:80px" maxlength="4" /></p>
               <p><label for="event"><span class="required">*</span> Event Name</label> <input type="text" name="event" id="event" value="<?php echo (!empty($_POST['event'])) ? stripslashes($_POST['event']) : ''; ?>" /></p>
               <p><label for="email"><span class="required">*</span> Event Email</label> <input type="text" name="email" id="email" value="<?php echo (!empty($_POST['email'])) ? stripslashes($_POST['email']) : ''; ?>" /></p>
               <p><label for="times">Time(s)</label> <input type="text" name="times" id="times" value="<?php echo (!empty($_POST['times'])) ? stripslashes($_POST['times']) : ''; ?>" /></p>
               <p><label for="location">Location</label> <input type="text" name="location" id="location" value="<?php echo (!empty($_POST['location'])) ? stripslashes($_POST['location']) : ''; ?>" /></p>
               <p><label for="description">Description</label> <textarea name="description" id="description" cols="40" rows="10" style="width:400px;"><?php echo (!empty($_POST['description'])) ? stripslashes($_POST['description']) : ''; ?></textarea></p>
               <p><div class="label">&nbsp;</div><input type="checkbox" name="registration" id="registration" value="1"<?php echo (isset($_POST['registration'])) ? ' checked="checked"' : ''; ?> style="width:auto;" /> <label for="registration" class="plain">Allow Registration</label></p>
               <p><label for="price">Registration Cost</label> <input type="text" name="price" id="price" value="<?php echo (!empty($_POST['price'])) ? stripslashes($_POST['price']) : ''; ?>" style="width:80px;" /></p>
               <p class="submit" style="padding-top:15px;"><input type="submit" name="submit" id="submit" value="Add Event" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>