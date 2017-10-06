<?php
	require('config.php');
	
	// Expired Session?
	if(isset($_GET['expired'])) $alerts->addAlert('Your session has expired. Please login again.');

	// Login
	if(isset($_POST['submit'])){
		try {
			$required = array('username', 'password');
			
			$validator = new Validator($required);
			$validator->noFilter('username');
			$validator->noFilter('password');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				$clean = array_map('escapeData', $filtered);
				
				$result = confirmPassword($clean['username'], $clean['password'], ACCESS_LEVEL_CONTRACTOR);
				if($result > 0){
					
					$user_info = getUser($result);
					if($user_info > 0){
																							
						$_SESSION['user_info'] = array();
						$_SESSION['user_info']['user_id'] = (int) $result;
						$_SESSION['user_info']['username'] = $clean['username'];
						$_SESSION['user_info']['access_level'] = (int) $user_info['access_level'];
												
						updateUserLoginInfo($result, escapeData($_SERVER['REMOTE_ADDR']), escapeData($_SERVER['HTTP_USER_AGENT']));
												
						if(!empty($_GET['return']))
							header("Location: ".ltrim($_SERVER['QUERY_STRING'], "?expired&return="));
						else if($_SESSION['user_info']['access_level']===ACCESS_LEVEL_ADMIN)
							header("Location: ../admin/information_links.php");
						else
							header("Location: resources.php");
							
						exit();
							
						exit();
					
					} else {
						$alerts->addAlert('You could not be logged-in. Please try again.');
					}
				} else {
					$alerts->addAlert('The login information you entered was incorrect.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again.');
		}
	}
	
	
	// Logout
	if(isset($_GET['logout'])){
		$_SESSION = array();
		@session_destroy();
		setcookie(session_name(), '', time()-300, '/', '', 0);
		$alerts->addAlert('You have been logged-out.');
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Contractor Login | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='login'; include("subnav_contractors.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Contractor Login</h1>
        
         <p>To view the following information please log in below:</p>
         
         <ul>
         	<li>News &amp; Events</li>
            <li>Territory Information</li>
            <li>Resources</li>
         </ul>
       
         <br style="clear:left;" />
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>" method="post">
         	<p><label for="username"><span class="required">*</span> Username</label> <input type="text" name="username" id="username" value="<?php echo (!empty($_POST['username'])) ? stripslashes($_POST['username']) : ''; ?>" /></p>
            <p><label for="password"><span class="required">*</span> Password</label> <input type="password" name="password" id="password" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Login" /></p>
         </form>
         
         <p style="padding:15px 0 0 140px;font-style:italic;"><a href="mailto:info@iwlocal3.com?subject=Contractor Login Request">Don’t Have A Contractor Account?</a></p>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>