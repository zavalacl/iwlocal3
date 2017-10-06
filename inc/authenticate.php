<?php
require_once('config.php');

// Authenticate User
if(!loggedIn()){
	if(isset($access_level) && $access_level == ACCESS_LEVEL_CONTRACTOR)
		header("Location: ".WEB_ROOT."contractors/login.php?expired&return=".$_SERVER['REQUEST_URI']);
		
	else if(isset($access_level) && $access_level == ACCESS_LEVEL_BENEFITS_DEPT)
		header("Location: ".WEB_ROOT."benefits/login.php?expired&return=".$_SERVER['REQUEST_URI']);
		
	else
		header("Location: ".WEB_ROOT."members/login.php?expired&return=".$_SERVER['REQUEST_URI']);
		
	exit();
}

// Make sure user is the correct type
if(isset($access_level) && $access_level){

	// Accept an array of access levels
	if(is_array($access_level)){
		
		if( !in_array($_SESSION['user_info']['access_level'], $access_level) && $_SESSION['user_info']['access_level'] !== ACCESS_LEVEL_ADMIN){
			include(HOME_ROOT."403.php");
			exit();
		}
		
	// Only one access level
	} else {

		if($_SESSION['user_info']['access_level'] !== $access_level && $_SESSION['user_info']['access_level'] !== ACCESS_LEVEL_ADMIN){
			include(HOME_ROOT."403.php");
			exit();
		}
	}
}

// Make sure user has admin privledges
if(isset($require_admin) && $require_admin){
	if($_SESSION['user_info']['access_level'] < ACCESS_LEVEL_ADMIN){
		include(HOME_ROOT."403.php");
		exit();
	}
}
