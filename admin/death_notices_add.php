<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/death_notices.php');
	
	
	// Add Death Notice
	if(isset($_POST['submit'])){
		try {
			$required = array('first_name', 'last_name', 'month', 'day', 'year');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->isInt('month');
			$validator->isInt('day');
			$validator->isInt('year');
			
			$validator->isInt('age');
			$validator->noFilter('book_number');
			$validator->isInt('years_member');
			$validator->noFilter('visitation');
			$validator->noFilter('funeral_service');
			$validator->noFilter('burial');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				$date = $clean['year'].'-'.$clean['month'].'-'.$clean['day'];
				
				if(newDeathNotice($clean['first_name'], $clean['last_name'], $clean['age'], $clean['book_number'], $clean['years_member'], $clean['visitation'], $clean['funeral_service'], $clean['burial'], $date) > 0){
					header("Location: death_notices.php?nn");
					exit;
				} else {
					$alerts->addAlert('The notice could not be added.');
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
<title>Admin: Death Notices | Iron Workers Local Union No. 3 | Pittsburgh and Erie Areas</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='death_notices'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="death_notices.php">Death Notices</a></li>
         	<li>Add Death Notice</li>
         </ul>
               
      	<h1>Admin: Add Death Notice</h1>
         
         <p>Complete the form below to add a death notice.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post">
         	<p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($_POST['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($_POST['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
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
               <p><label for="age">Age</label> <input type="text" name="age" id="age" value="<?php echo htmlentities($_POST['age'], ENT_QUOTES, 'utf-8'); ?>" style="width:60px;" /></p>
               <p><label for="book_number">Book Number</label> <input type="text" name="book_number" id="book_number" value="<?php echo htmlentities($_POST['book_number'], ENT_QUOTES, 'utf-8'); ?>" /></p>
               <p><label for="years_member">Years a Member</label> <input type="text" name="years_member" id="years_member" value="<?php echo htmlentities($_POST['years_member'], ENT_QUOTES, 'utf-8'); ?>" style="width:60px;" /></p>
               <div class="label">Visitation</div><br /><textarea name="visitation" id="visitation" class="wysiwyg"><?php echo htmlentities($_POST['visitation'], ENT_QUOTES, 'utf-8'); ?></textarea>
               <div class="label">Funeral Service</div><br /><textarea name="funeral_service" id="funeral_service" class="wysiwyg"><?php echo htmlentities($_POST['funeral_service'], ENT_QUOTES, 'utf-8'); ?></textarea>
               <div class="label">Burial</div><br /><textarea name="burial" id="burial" class="wysiwyg"><?php echo htmlentities($_POST['burial'], ENT_QUOTES, 'utf-8'); ?></textarea>
               <p class="submit"><input type="submit" name="submit" id="submit" value="Add Notice" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>