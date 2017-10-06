<?php

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno){

	$error = "The server is currently experiencing some technical difficulties. Please try again a little later.";
	if($verbose) $error .= " (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	
	include("error_page.php");
	exit;
}
