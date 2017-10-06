<?php

function newRepositoryDocument($title, $filename, $original_filename, $comment, $verbose=false){
	$query = "INSERT INTO `documents_repository` (`title`, `filename`, `original_filename`, `comment`, `date`) VALUES ('$title', '$filename', '$original_filename', '$comment', NOW())";
	return insertQuery($query, $verbose);
}

function editRepositoryDocument($document_id, $title, $comment, $verbose=false){
	$query = "UPDATE `documents_repository` SET `title`='$title', `comment`='$comment' WHERE `document_id`=$document_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function replaceRepositoryDocumentFile($document_id, $filename, $original_filename, $verbose=false){
	$document_info = getRepositoryDocument($document_id, $verbose);
	
	$query = "UPDATE `documents_repository` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		
		global $file_paths;
		@unlink($file_paths['document_repository'].$document_info['filename']);
	}
	return $result;
}

function getRepositoryDocuments($verbose=false){
	$query = "SELECT * FROM `documents_repository` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verboses);
}

function getRepositoryDocument($document_id, $verbose=false){
	$query = "SELECT * FROM `documents_repository` WHERE `document_id`=$document_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementRepositoryDocumentDownloads($document_id, $verbose=false){
	$query = "UPDATE `documents_repository` SET `downloads`=`downloads`+1 WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function deleteRepositoryDocument($document_id, $verbose=false){
	$document_info = getRepositoryDocument($document_id, $verbose);
	
	$query = "DELETE FROM `documents_repository` WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0){
		global $file_paths;
		@unlink($file_paths['document_repository'].$document_info['filename']);
	}
	return $result;
}

?>