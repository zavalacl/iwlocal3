<?php

function newPoliticalActionFile($title, $comment, $verbose=false){
	$query = "INSERT INTO `political_action_files` (`title`, `comment`, `date`) VALUES ('$title', '$comment', NOW())";
	return insertQuery($query, $verbose);
}

function editPoliticalActionFile($file_id, $title, $comment, $verbose=false){
	$query = "UPDATE `political_action_files` SET `title`='$title', `comment`='$comment' WHERE `file_id`=$file_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editPoliticalActionFileURL($file_id, $url, $verbose=false){
	$query = "UPDATE `political_action_files` SET `url`='$url' WHERE `file_id`=$file_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editPoliticalActionFileFile($file_id, $filename, $original_filename, $verbose=false){
	$file_info = getPoliticalActionFile($file_id, $verbose);
	
	$query = "UPDATE `political_action_files` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `file_id`=$file_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0 && $file_info['filename']){
		
		global $file_paths;
		@unlink($file_paths['political_action_files'].$file_info['filename']);
	}
	return $result;
}

function editPoliticalActionFileImage($file_id, $image, $verbose=false){
	$file_info = getPoliticalActionFile($file_id, $verbose);
	
	$query = "UPDATE `political_action_files` SET `image`='$image' WHERE `file_id`=$file_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0 && $file_info['image']){
		
		global $file_paths;
		@unlink($file_paths['political_action_files'].$file_info['image']);
	}
	return $result;
}

function getPoliticalActionFiles($verbose=false){
	$query = "SELECT * FROM `political_action_files` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verboses);
}

function getPoliticalActionFile($file_id, $verbose=false){
	$query = "SELECT * FROM `political_action_files` WHERE `file_id`=$file_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementPoliticalActionFileDownloads($file_id, $verbose=false){
	$query = "UPDATE `political_action_files` SET `downloads`=`downloads`+1 WHERE `file_id`=$file_id LIMIT 1";
	return query($query, $verbose);
}

function deletePoliticalActionFile($file_id, $verbose=false){
	$file_info = getPoliticalActionFile($file_id, $verbose);
	
	$query = "DELETE FROM `political_action_files` WHERE `file_id`=$file_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0 && $file_info['filename']){
		
		global $file_paths;
		@unlink($file_paths['political_action_files'].$file_info['filename']);
	}
	
	if($result > 0 && $file_info['image']){
		
		global $file_paths;
		@unlink($file_paths['political_action_files'].$file_info['image']);
	}
	
	return $result;
}

?>