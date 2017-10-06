<?php

/* Information Links */
function newInformationLink($title, $verbose=false){
	$query = "INSERT INTO `announcements_info_links` (`title`, `date`) VALUES ('$title', NOW())";
	return insertQuery($query, $verbose);
}

function editInformationLink($link_id, $title, $verbose=false){
	$query = "UPDATE `announcements_info_links` SET `title`='$title' WHERE `link_id`=$link_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editInformationLinkURL($link_id, $url, $verbose=false){
	$query = "UPDATE `announcements_info_links` SET `url`='$url' WHERE `link_id`=$link_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editInformationLinkFile($link_id, $filename, $original_filename, $verbose=false){
	$query = "UPDATE `announcements_info_links` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `link_id`=$link_id LIMIT 1";
	return query($query, $verbose);
}

function getInformationLinks($verbose=false){
	$query = "SELECT * FROM `announcements_info_links` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verbose);
}

function getInformationLink($link_id, $verbose=false){
	$query = "SELECT * FROM `announcements_info_links` WHERE `link_id`=$link_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementInformationLinkDownloads($link_id, $verbose=false){
	$query = "UPDATE `announcements_info_links` SET `downloads`=`downloads`+1 WHERE `link_id`=$link_id LIMIT 1";
	return query($query, $verbose);
}

function deleteInformationLink($link_id, $verbose=false){
	$document_info = getInformationLink($link_id, $verbose);
	$query = "DELETE FROM `announcements_info_links` WHERE `link_id`=$link_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['info_links'].$document_info['filename']);
	}
	return $result;
}



/* Job Pictures */
function newJobPictureLink($title, $verbose=false){
	$query = "INSERT INTO `announcements_job_pictures` (`title`, `date`) VALUES ('$title', NOW())";
	return insertQuery($query, $verbose);
}

function editJobPictureLink($document_id, $title, $verbose=false){
	$query = "UPDATE `announcements_job_pictures` SET `title`='$title' WHERE `document_id`=$document_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editJobPictureLinkFile($document_id, $filename, $original_filename, $verbose=false){
	$query = "UPDATE `announcements_job_pictures` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function getJobPictureLinks($verbose=false){
	$query = "SELECT * FROM `announcements_job_pictures` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verbose);
}

function getJobPictureLink($document_id, $verbose=false){
	$query = "SELECT * FROM `announcements_job_pictures` WHERE `document_id`=$document_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementJobPictureLinkDownloads($document_id, $verbose=false){
	$query = "UPDATE `announcements_job_pictures` SET `downloads`=`downloads`+1 WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function deleteJobPictureLink($document_id, $verbose=false){
	$document_info = getJobPictureLink($document_id, $verbose);
	$query = "DELETE FROM `announcements_job_pictures` WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['job_pictures'].$document_info['filename']);
	}
	return $result;
}



/* Other Links */
function newLink($title, $verbose=false){
	$query = "INSERT INTO `announcements_links` (`title`, `date`) VALUES ('$title', NOW())";
	return insertQuery($query, $verbose);
}

function editLink($link_id, $title, $verbose=false){
	$query = "UPDATE `announcements_links` SET `title`='$title' WHERE `link_id`=$link_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editLinkURL($link_id, $url, $verbose=false){
	$query = "UPDATE `announcements_links` SET `url`='$url' WHERE `link_id`=$link_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editLinkFile($link_id, $filename, $original_filename, $verbose=false){
	$query = "UPDATE `announcements_links` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `link_id`=$link_id LIMIT 1";
	return query($query, $verbose);
}

function getLinks($verbose=false){
	$query = "SELECT * FROM `announcements_links` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verbose);
}

function getLink($link_id, $verbose=false){
	$query = "SELECT * FROM `announcements_links` WHERE `link_id`=$link_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementLinkDownloads($link_id, $verbose=false){
	$query = "UPDATE `announcements_links` SET `downloads`=`downloads`+1 WHERE `link_id`=$link_id LIMIT 1";
	return query($query, $verbose);
}

function deleteLink($link_id, $verbose=false){
	$document_info = getLink($link_id, $verbose);
	$query = "DELETE FROM `announcements_links` WHERE `link_id`=$link_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['links'].$document_info['filename']);
	}
	return $result;
}

?>