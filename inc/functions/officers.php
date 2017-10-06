<?php

/* Officer Categories */
function getOfficerCategories($verbose=false){
	$query = "SELECT * FROM `officers_categories` ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}




/* Officers */
function newOfficer($category_id, $first_name, $last_name, $title, $verbose=false){
	$position = getMaxOfficerPosition($category_id, $verbose) + 1;
	
	$query = "INSERT INTO `officers` (`category_id`, `first_name`, `last_name`, `title`, `position`) VALUES ($category_id, '$first_name', '$last_name', '$title', $position)";
	return insertQuery($query, $verbose);
}

function editOfficer($officer_id, $category_id, $first_name, $last_name, $title, $verbose=false){
	$query = "UPDATE `officers` SET `category_id`=$category_id, `first_name`='$first_name', `last_name`='$last_name', `title`='$title' WHERE `officer_id`=$officer_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateOfficerImage($officer_id, $filename, $verbose=false){
	$officer_info = getOfficer($officer_id, $verbose);
	
	$query = "UPDATE `officers` SET `image`='$filename' WHERE `officer_id`=$officer_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $officer_info['image']){
		global $file_paths;
		unlink($file_paths['officer_images'].$officer_info['image']);
	}
	return $result;
}

function updateOfficerPosition($officer_id, $position, $verbose=false){
	$query = "UPDATE `officers` SET `position`=$position WHERE `officer_id`=$officer_id LIMIT 1";
	return query($query, $verbose);
}

function getOfficer($officer_id, $verbose=false){
	$query = "SELECT * FROM `officers` WHERE `officer_id`=$officer_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getOfficers($category_id, $verbose=false){
	$query = "SELECT * FROM `officers` WHERE `category_id`=$category_id ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}

function getMaxOfficerPosition($category_id, $verbose=false){
	$query = "SELECT MAX(`position`) FROM `officers` WHERE `category_id`=$category_id";
	$result = selectQuery($query, $verbose);
	if($result > 0){
		return $result[0];
	} else {
		return 0;
	}
}

function deleteOfficer($officer_id, $verbose=false){
	$officer_info = getOfficer($officer_id, $verbose);
	
	$query = "DELETE FROM `officers` WHERE `officer_id`=$officer_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $officer_info['image']){
		global $file_paths;
		unlink($file_paths['officer_images'].$officer_info['image']);
	}
	return $result;
}

?>