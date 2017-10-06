<?php
	require('config.php');
	
	// Expired Session?
	if(isset($_GET['expired'])) $alerts->addAlert('Your session has expired. Please login again.');
	
	// Logout
	if(isset($_GET['logout'])){
		session_start();
		$_SESSION = array();
		@session_destroy();
		setcookie(session_name(), '', time()-300, '/', '', 0);
		$alerts->addAlert('You have been logged-out.');
	}
	
	// If already logged in, redirect to members area
	if(loggedIn()){
		header("Location: announcements.php");
		exit();
	}

	// Login
	if(isset($_POST['submit'])){
		try {
			$required = array('book_number', 'password');
			
			$validator = new Validator($required);
			$validator->noFilter('book_number');
			$validator->noFilter('password');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				$clean = array_map('escapeData', $filtered);
				
				$result = confirmPassword($clean['book_number'], $clean['password'], ACCESS_LEVEL_MEMBER);
				if($result > 0){
					
					$user_info = getUser($result);
					if($user_info > 0){
																							
						$_SESSION['user_info'] = array();
						$_SESSION['user_info']['user_id'] = (int) $result;
						$_SESSION['user_info']['username'] = $clean['book_number'];
						$_SESSION['user_info']['access_level'] = (int) $user_info['access_level'];
												
						updateUserLoginInfo($result, escapeData($_SERVER['REMOTE_ADDR']), escapeData($_SERVER['HTTP_USER_AGENT']));
												
						if(!empty($_GET['return']))
							header("Location: ".ltrim($_SERVER['QUERY_STRING'], "?expired&return="));
						else if($_SESSION['user_info']['access_level']===ACCESS_LEVEL_ADMIN)
							header("Location: ../admin/information_links.php");
						else
							header("Location: announcements.php");
							
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Member Login | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	
      </div><!-- div.left -->
      <div class="right">
      	<h1>Member Login</h1>
         
         <p>Welcome members!<br />
			Following you will find information that is exclusively available to members of Iron Workers Local Union No. 3. The information provided will be helpful in learning of the latest industry news and events.</p> 

			<p>To gain access, you will need to enter your book number and password.</p>
       
         <br style="clear:left;" />
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?><?php echo (!empty($_GET['return'])) ? '?'.$_SERVER['QUERY_STRING'] : ''; ?>" method="post">
         	<p><label for="book_number"><span class="required">*</span> Book Number</label> <input type="text" name="book_number" id="book_number" value="<?php echo (!empty($_POST['book_number'])) ? stripslashes($_POST['book_number']) : ''; ?>" /></p>
            <p><label for="password"><span class="required">*</span> Password</label> <input type="password" name="password" id="password" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Login" /></p>
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>