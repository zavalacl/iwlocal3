<?php

/* User Accounts */
function createUser($first_name, $last_name, $username, $password, $local_number, $class, $month_dues_paid, $verbose=false){
	$encrypted = encryptPassword($username, $password);
	$month_dues_paid = ($month_dues_paid) ? "'$month_dues_paid'" : "NULL";
	$class = ($class) ? "'$class'" : "NULL";
	
	$query = "INSERT INTO `users` (`first_name`, `last_name`, `username` ,`salt`, `hash`, `local_number`, `class`, `month_dues_paid`) VALUES ('$first_name', '$last_name', '$username', '{$encrypted['salt']}', '{$encrypted['hash']}', '$local_number', $class, $month_dues_paid)";
	return insertQuery($query, $verbose);
}

function editUser($user_id, $first_name, $last_name, $local_number, $class, $month_dues_paid, $verbose=false){
	$month_dues_paid = ($month_dues_paid) ? "'$month_dues_paid'" : "NULL";
	$class = ($class) ? "'$class'" : "NULL";
	
	$query = "UPDATE `users` SET `first_name`='$first_name', `last_name`='$last_name', `local_number`='$local_number', `class`=$class, `month_dues_paid`=$month_dues_paid WHERE `user_id`=$user_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateUserStatus($user_id, $status, $verbose=false){
	$query = "UPDATE `users` SET `active`=$status WHERE `user_id`=$user_id LIMIT 1";
	return query($query, $verbose);
}

function updateUserAccessLevel($user_id, $access_level, $verbose=false){
	$query = "UPDATE `users` SET `access_level`=$access_level WHERE `user_id`=$user_id LIMIT 1";
	return query($query, $verbose);
}

function updateUserLoginInfo($user_id, $ip_address, $user_agent, $verbose=false){
	$query = "UPDATE `users` SET `last_login`=NOW(), `last_ip`='$ip_address', `last_user_agent`='$user_agent' WHERE `user_id`=$user_id LIMIT 1";
	return query($query, $verbose);
}

function confirmPassword($username, $password, $access_level=false, $verbose=false){
	$access_level = ($access_level) ? "AND (`access_level`=$access_level OR `access_level`=".ACCESS_LEVEL_ADMIN.")" : "";
	$query = "SELECT `user_id`, `salt`, `hash` FROM `users` WHERE `username`='$username' AND `active`=1 $access_level LIMIT 1";
	$result = selectQuery($query, $verbose);
	if($result > 0){
		if($result['hash'] === sha1($username.$result['salt'].$password)){
			return $result['user_id'];
		}
	}
	return 0;
}

function changePassword($user_id, $username, $password, $verbose=false){
	$pass = encryptPassword($username, $password);
	$query = "UPDATE `users` SET `hash`='{$pass['hash']}', `salt`='{$pass['salt']}' WHERE `user_id`=$user_id LIMIT 1";
	return query($query, $verbose);
}

function encryptPassword($username, $password){
	$salt = uniqid(time()+rand(1,99));
	$hash = sha1($username.$salt.$password);
	$result['salt'] = $salt;
	$result['hash'] = $hash;
	return $result;
}

function getUser($user_id, $verbose=false){
	$query = "SELECT * FROM `users` WHERE `user_id`=$user_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getUserByUsername($username, $verbose=false){
	$query = "SELECT * FROM `users` WHERE `username`='$username' LIMIT 1";
	return selectQuery($query, $verbose);
}

function deleteUser($user_id, $verbose=false){
	$query = "DELETE FROM `users` WHERE `user_id`=$user_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		
	}
	return $result;
}

function getUsers($verbose=false){
	$query = "SELECT `user_id`, `first_name`, `last_name`, `username`, `access_level`, `active`, `last_login` FROM `users` ORDER BY `last_name` ASC, `first_name` ASC";
	return selectArrayQuery($query, $verbose);
}

function printUserType($type){
	switch($type){
	case ACCESS_LEVEL_ADMIN :
		return 'Admin';
	case ACCESS_LEVEL_MEMBER :
		return 'Member';
	case ACCESS_LEVEL_CONTRACTOR :
		return 'Contractor';
	case ACCESS_LEVEL_BENEFITS_DEPT :
		return 'Benefits Department';
	default :
		return 'Unknown';
	}
}

function getUserId(){
	return $_SESSION['user_info']['user_id'];
}
