<?php

// The domain name
$main_domain 		= 'iwlocal3.com';
$mobile_domain 	= 'm.iwlocal3.com';


// View full website?
if(isset($_GET['full-site'])){
	$view_full_site = true;
	setcookie('full-site', true, 0, '/', $main_domain);
	setcookie('full-site', true, 0, '/', $mobile_domain);
}


// View mobile website?
if(isset($_GET['mobile-site'])){
	$view_full_site = false;
	setcookie('full-site', false, 0, '/', $main_domain);
	setcookie('full-site', false, 0, '/', $mobile_domain);
}


// Check for Mobile Browser
$user_agent = $_SERVER['HTTP_USER_AGENT'];
$pattern = '/IEMobile|Windows CE|NetFront|PlayStation|PLAYSTATION|iPhone|MIDP|UP\.Browser|Symbian|Nintendo|Android|Verizon/';

if(preg_match($pattern, $user_agent, $matches) > 0){
	$mobile_browser = true;
	
	// Send to mobile website?
	if($view_full_site !== true && $_COOKIE['full-site'] != true && $_SERVER['HTTP_HOST'] != $mobile_domain){	
		header('Location: http://'.$mobile_domain);
		exit();
	}
}

?>