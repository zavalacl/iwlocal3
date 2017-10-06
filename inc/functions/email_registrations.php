<?php

function newEmailRegistration($email, $first_name, $last_name, $verbose=false){
	$query = "INSERT INTO `email_registrations` (`email`, `first_name`, `last_name`, `date`) VALUES ('$email', '$first_name', '$last_name', NOW())";
	return insertQuery($query, $verbose);
}

function getEmailRegistrationByEmail($email, $verbose=false){
	$query = "SELECT * FROM `email_registrations` WHERE `email`='$email' LIMIT 1";
	return selectQuery($query, $verbose);
}

function getEmailRegistrations($verbose=false){
	$query = "SELECT * FROM `email_registrations` ORDER BY `last_name` ASC, `first_name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteEmailRegistration($registration_id, $verbose=false){
	$query = "DELETE FROM `email_registrations` WHERE `registration_id`=$registration_id LIMIT 1";
	return query($query, $verbose);
}

?>