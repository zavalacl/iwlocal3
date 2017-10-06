<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require_once('functions/users.php');
	
	
	// Add User Account
	if(isset($_POST['submit'])){
		try {
			$required = array('username', 'password', 'password_2', 'access_level');
			
			$validator = new Validator($required);
			$validator->noFilter('username');
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('local_number');
			
			$validator->isInt('class');
			$validator->isInt('month_dues_paid_year');
			$validator->isInt('month_dues_paid_month');
			$validator->isInt('month_dues_paid_day');
			$validator->noFilter('password');
			$validator->noFilter('password_2');
			$validator->isInt('access_level');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				if($filtered['password']===$filtered['password_2']){
				
					$clean = array_map('escapeData', $filtered);
					
					// Make sure username/book number isn't already in use
					if(getUserByUsername($clean['username']) <= 0){
						
						// Dues paid through date
						if($clean['month_dues_paid_year'] && $clean['month_dues_paid_month'] && $clean['month_dues_paid_day']){
							$month_dues_paid = $clean['month_dues_paid_year'].'-'.$clean['month_dues_paid_month'].'-'.$clean['month_dues_paid_day'];
						} else {
							$month_dues_paid = false;
						}
					
						$result = createUser($clean['first_name'], $clean['last_name'], $clean['username'], $clean['password'], $clean['local_number'], $clean['class'], $month_dues_paid);
						if($result > 0){
							
							$status = (isset($_POST['active'])) ? 1 : 0;
							
							updateUserAccessLevel($result, $clean['access_level']);
							updateUserStatus($result, $status);
							
							header("Location: users.php?nu");
							exit;
						} else {
							$alerts->addAlert('The user account could not be added.');
						}
					} else {
						$alerts->addAlert('A user account already exists with that username/book number.');				
					}
				} else {
					$alerts->addAlert('The passwords you entered did not match.');
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
<title>Admin: Add User Account | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='users'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="users.php">User Accounts</a></li>
         	<li>Add User Account</li>
         </ul>
               
      	<h1>Admin: Add User Account</h1>
         
         <p>Complete the form below to create a new user account.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post">
            <p><label for="username"><span class="required">*</span> Username/Book Number</label> <input type="text" name="username" id="username" value="<?php echo htmlentities($_POST['username'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="first_name">First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($_POST['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="last_name">Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($_POST['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="local_number">Local Number</label> <input type="text" name="local_number" id="local_number" value="<?php echo htmlentities($_POST['local_number'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            
            <p>
            	<div class="label">Class</div>
            	<input type="radio" name="class" id="class_journeyman" value="<?php echo WORKER_TYPE_JOURNEYMAN; ?>"<?php echo (empty($_POST['class']) || $_POST['class']==WORKER_TYPE_JOURNEYMAN) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_journeyman" class="plain">Journeyman</label>
      			<input type="radio" name="class" id="class_apprentice" value="<?php echo WORKER_TYPE_APPRENTICE; ?>"<?php echo ($_POST['class']==WORKER_TYPE_APPRENTICE) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_apprentice" class="plain">Apprentice</label> 
      			<input type="radio" name="class" id="class_probationary" value="<?php echo WORKER_TYPE_PROBATIONARY; ?>"<?php echo ($_POST['class']==WORKER_TYPE_PROBATIONARY) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_probationary" class="plain">Probationary</label>
            </p>
            <p><div class="label"><span class="required">*</span> Month Dues Paid</div>
            	<select name="month_dues_paid_month" id="month_dues_paid_month" style="width:auto;">
               	<option value=""></option>
<?php
			for($i=1; $i<=12; $i++){
?>
					<option value="<?php echo $i; ?>"<?php echo ((!empty($_POST['month_dues_paid_month']) && $_POST['month_dues_paid_month']==$i) || (empty($_POST['month_dues_paid_month']) && $i==date('n'))) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php
			}
?>
               </select> 
               <select name="month_dues_paid_day" id="month_dues_paid_day" style="width:auto;">
               	<option value=""></option>
<?php
			for($j=1; $j<=31; $j++){
?>
					<option value="<?php echo $j; ?>"<?php echo ((!empty($_POST['month_dues_paid_day']) && $_POST['month_dues_paid_day']==$j) || (empty($_POST['month_dues_paid_day']) && $j==date('j'))) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php
			}
?>
               </select> 
               <input type="text" name="month_dues_paid_year" id="month_dues_paid_year" value="<?php echo (!empty($_POST['month_dues_paid_year'])) ? $_POST['month_dues_paid_year'] : date('Y'); ?>" style="width:80px" maxlength="4" />
            </p>
            <p><label for="password"><span class="required">*</span> Password</label> <input type="password" name="password" id="password" value="<?php echo (!empty($_POST['password'])) ? stripslashes($_POST['password']) : ''; ?>" /></p>
            <p><label for="password_2"><span class="required">*</span> Confirm Password</label> <input type="password" name="password_2" id="password_2" value="<?php echo (!empty($_POST['password_2'])) ? stripslashes($_POST['password_2']) : ''; ?>" /></p>
            <p><label for="username"><span class="required">*</span> User Type</label> 
            	<select name="access_level" id="access_level">
               	<option value="<?php echo ACCESS_LEVEL_MEMBER; ?>"<?php echo (empty($_POST['access_level']) || (!empty($_POST['access_level']) && $_POST['access_level']==ACCESS_LEVEL_MEMBER)) ? ' selected="selected"' : ''; ?>>Member</option>
                  <option value="<?php echo ACCESS_LEVEL_CONTRACTOR; ?>"<?php echo ((!empty($_POST['access_level']) && $_POST['access_level']==ACCESS_LEVEL_CONTRACTOR)) ? ' selected="selected"' : ''; ?>>Contractor</option>
                  <option value="<?php echo ACCESS_LEVEL_BENEFITS_DEPT; ?>"<?php echo ((!empty($_POST['access_level']) && $_POST['access_level']==ACCESS_LEVEL_BENEFITS_DEPT)) ? ' selected="selected"' : ''; ?>>Benefits Department</option>
                  <option value="<?php echo ACCESS_LEVEL_ADMIN; ?>"<?php echo (!empty($_POST['access_level']) && $_POST['access_level']==ACCESS_LEVEL_ADMIN) ? ' selected="selected"' : ''; ?>>Admin</option>
               </select>
            </p>
            <p><label for="active">Active?</label> <input type="checkbox" name="active" id="active" value="1"<?php echo (isset($_POST['active'])) ? ' checked="checked"' : ''; ?> style="width:auto;" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Add User Account" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>