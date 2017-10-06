<?php

function newApprenticeshipApplication($filename, $original_filename, $hash, $verbose=false){
	$query = "INSERT INTO `apprenticeship_applications` (`filename`, `original_filename`, `hash`, `date`) VALUES ('$filename', '$original_filename', '$hash', NOW())";
	return insertQuery($query, $verbose);
}

function getApprenticeshipApplication($application_id, $hash=false, $verbose=false){
	$hash = ($hash) ? "AND `hash`='$hash'" : "";
	$query = "SELECT * FROM `apprenticeship_applications` WHERE `application_id`=$application_id $hash LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementApprenticeshipApplicationDownloads($application_id, $verbose=false){
	$query = "UPDATE `apprenticeship_applications` SET `downloads`=`downloads`+1 WHERE `application_id`=$application_id LIMIT 1";
	return query($query, $verbose);
}

?>