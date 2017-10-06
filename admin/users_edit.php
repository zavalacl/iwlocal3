<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require_once('functions/users.php');
	
	$user_id = (int) $_GET['uid'];
	
	// Edit User Account
	if(isset($_POST['submit'])){
		try {
			$required = array('access_level');
			
			$validator = new Validator($required);
			$validator->isInt('access_level');
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('local_number');
			
			$validator->isInt('class');
			$validator->isInt('month_dues_paid_year');
			$validator->isInt('month_dues_paid_month');
			$validator->isInt('month_dues_paid_day');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				// Dues paid through date
				if($clean['month_dues_paid_year'] && $clean['month_dues_paid_month'] && $clean['month_dues_paid_day']){
					$month_dues_paid = $clean['month_dues_paid_year'].'-'.$clean['month_dues_paid_month'].'-'.$clean['month_dues_paid_day'];
				} else {
					$month_dues_paid = false;
				}
								
				// Info
				if(!editUser($user_id, $clean['first_name'], $clean['last_name'], $clean['local_number'], $clean['class'], $month_dues_paid)){
					$alerts->addAlert('The user\'s info could not be updated.');
				}
				
				$user_info = getUser($user_id);
				
				$status = (isset($_POST['active'])) ? 1 : 0;
				
				// Status
				if($status != $user_info['active']){
					if(updateUserStatus($user_id, $status) <=0){
						$success = false;
						$alerts->addAlert('The user\'s status could not be updated.');
					}
				}
				
				// Access Level
				if($clean['access_level'] != $user_info['access_level']){
					if(updateUserAccessLevel($user_id, $clean['access_level']) <=0){
						$success = false;
						$alerts->addAlert('The user\'s user type/access level could not be updated.');
					}
				}
				
				// Password
				if(!empty($_POST['password']) && !empty($_POST['password_2'])){
					$password = escapeData($_POST['password']);
					$password_2 = escapeData($_POST['password_2']);
					if($password===$password_2){
						if(changePassword($user_id, escapeData($user_info['username']), $password) <= 0){
							$alerts->addAlert('The password could not be changed.');
						}
					} else {
						$success = false;
						$alerts->addAlert('The password could not be changed: The passwords you entered did not match.');
					}
				}
				
				if(!$alerts->hasAlerts()){
					$alerts->addAlert('The user\'s info was successfully updated.', 'success');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Get User Info
	$user_info = getUser($user_id);
	if($user_info['month_dues_paid']) list($year, $month, $day) = explode('-', $user_info['month_dues_paid']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Edit User Account | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
         	<li>Edit User Account</li>
         </ul>
               
      	<h1>Admin: Edit User Account</h1>
         
         <p>Complete the form below to update a user's account info.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
					<form action="<?php echo getSelf(); ?>?uid=<?php echo $user_id; ?>" method="post">
						<p>
							<div class="label">Username/Book Number</div>
							<?php echo $user_info['username']; ?>
						</p>
            <p><label for="username"><span class="required">*</span> User Type</label> 
            	<select name="access_level" id="access_level">
               	<option value="<?php echo ACCESS_LEVEL_MEMBER; ?>"<?php echo ($user_info['access_level']==ACCESS_LEVEL_MEMBER) ? ' selected="selected"' : ''; ?>>Member</option>
                  <option value="<?php echo ACCESS_LEVEL_CONTRACTOR; ?>"<?php echo ($user_info['access_level']==ACCESS_LEVEL_CONTRACTOR) ? ' selected="selected"' : ''; ?>>Contractor</option>
                  <option value="<?php echo ACCESS_LEVEL_BENEFITS_DEPT; ?>"<?php echo ($user_info['access_level']==ACCESS_LEVEL_BENEFITS_DEPT) ? ' selected="selected"' : ''; ?>>Benefits Department</option>
                  <option value="<?php echo ACCESS_LEVEL_ADMIN; ?>"<?php echo ($user_info['access_level']==ACCESS_LEVEL_ADMIN) ? ' selected="selected"' : ''; ?>>Admin</option>
               </select>
            </p>
            <p><label for="first_name">First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($user_info['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="last_name">Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($user_info['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            <p><label for="local_number">Local Number</label> <input type="text" name="local_number" id="local_number" value="<?php echo htmlentities($user_info['local_number'], ENT_QUOTES, 'utf-8'); ?>" /></p>
            
            <p>
            	<div class="label">Class</div>
            	<input type="radio" name="class" id="class_journeyman" value="<?php echo WORKER_TYPE_JOURNEYMAN; ?>"<?php echo ($user_info['class']==WORKER_TYPE_JOURNEYMAN) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_journeyman" class="plain">Journeyman</label>
      			<input type="radio" name="class" id="class_apprentice" value="<?php echo WORKER_TYPE_APPRENTICE; ?>"<?php echo ($user_info['class']==WORKER_TYPE_APPRENTICE) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_apprentice" class="plain">Apprentice</label> 
      			<input type="radio" name="class" id="class_probationary" value="<?php echo WORKER_TYPE_PROBATIONARY; ?>"<?php echo ($user_info['class']==WORKER_TYPE_PROBATIONARY) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="class_probationary" class="plain">Probationary</label>
            </p>
            <p><div class="label"><span class="required">*</span> Month Dues Paid</div>
            	<select name="month_dues_paid_month" id="month_dues_paid_month" style="width:auto;">
               	<option value=""></option>
<?php
			for($i=1; $i<=12; $i++){
?>
					<option value="<?php echo $i; ?>"<?php echo ($month==$i) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php
			}
?>
               </select> 
               <select name="month_dues_paid_day" id="month_dues_paid_day" style="width:auto;">
               	<option value=""></option>
<?php
			for($j=1; $j<=31; $j++){
?>
					<option value="<?php echo $j; ?>"<?php echo ($day==$j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php
			}
?>
               </select> 
               <input type="text" name="month_dues_paid_year" id="month_dues_paid_year" value="<?php echo $year; ?>" style="width:80px" maxlength="4" />
            </p>
            
            <p><label for="active">Active?</label> <input type="checkbox" name="active" id="active" value="1"<?php echo ($user_info['active']) ? ' checked="checked"' : ''; ?> style="width:auto;" /></p>
            
            <p><label for="password">Change Password</label> <input type="password" name="password" id="password" value="" /></p>
            <p><label for="password_2">Confirm Password</label> <input type="password" name="password_2" id="password_2" value="" /></p>
            
            <p class="submit"><input type="submit" name="submit" id="submit" value="Edit User Account" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>