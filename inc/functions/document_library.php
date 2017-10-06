<?php

function newDocument($title, $comment, $verbose=false){
	$query = "INSERT INTO `documents` (`title`, `comment`, `date`) VALUES ('$title', '$comment', NOW())";
	return insertQuery($query, $verbose);
}

function editDocument($document_id, $title, $comment, $verbose=false){
	$query = "UPDATE `documents` SET `title`='$title', `comment`='$comment' WHERE `document_id`=$document_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editDocumentURL($document_id, $url, $verbose=false){
	$query = "UPDATE `documents` SET `url`='$url' WHERE `document_id`=$document_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editDocumentFile($document_id, $filename, $original_filename, $verbose=false){
	$document_info = getDocument($document_id, $verbose);
	
	$query = "UPDATE `documents` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['documents'].$document_info['filename']);
	}
	return $result;
}

function getDocuments($verbose=false){
	$query = "SELECT * FROM `documents` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verboses);
}

function getDocument($document_id, $verbose=false){
	$query = "SELECT * FROM `documents` WHERE `document_id`=$document_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementDocumentDownloads($document_id, $verbose=false){
	$query = "UPDATE `documents` SET `downloads`=`downloads`+1 WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function deleteDocument($document_id, $verbose=false){
	$document_info = getDocument($document_id, $verbose);
	
	$query = "DELETE FROM `documents` WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0 && $document_info['filename']){
		global $file_paths;
		@unlink($file_paths['documents'].$document_info['filename']);
	}
	return $result;
}

?>