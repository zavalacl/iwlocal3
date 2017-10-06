<?php

function newScholarshipApplication($title, $description, $active=1, $verbose=false){
	$query = "INSERT INTO `scholarship_applications` (`title`, `description`, `active`) VALUES ('$title', '$description', $active)";
	return insertQuery($query, $verbose);
}

function editScholarshipApplication($application_id, $title, $description, $active, $verbose=false){
	$query = "UPDATE `scholarship_applications` SET `title`='$title', `description`='$description', `active`=$active WHERE `application_id`=$application_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editScholarshipApplicationFile($application_id, $filename, $original_filename, $verbose=false){
	$query = "UPDATE `scholarship_applications` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `application_id`=$application_id LIMIT 1";
	return query($query, $verbose);
}

function getScholarshipApplications($active=false, $verbose=false){
	$active = ($active!==false) ? "WHERE `active`=$active" : "";
	
	$query = "SELECT * FROM `scholarship_applications` $active ORDER BY `title` ASC";
	return selectArrayQuery($query, $verboses);
}

function getScholarshipApplication($application_id, $verbose=false){
	$query = "SELECT * FROM `scholarship_applications` WHERE `application_id`=$application_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementScholarshipApplicationDownloads($application_id, $verbose=false){
	$query = "UPDATE `scholarship_applications` SET `downloads`=`downloads`+1 WHERE `application_id`=$application_id LIMIT 1";
	return query($query, $verbose);
}

function deleteScholarshipApplication($application_id, $verbose=false){
	$document_info = getScholarshipApplication($application_id, $verbose);
	$query = "DELETE FROM `scholarship_applications` WHERE `application_id`=$application_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['scholarship_applications'].$document_info['filename']);
	}
	return $result;
}

?>