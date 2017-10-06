<?php

function editContentArea($key, $content, $verbose=false){
	$query = "UPDATE `content_areas` SET `content`='$content' WHERE `key`='$key' LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getContentArea($key, $verbose=false){
	$query = "SELECT * FROM `content_areas` WHERE `key`='$key' LIMIT 1";
	return selectQuery($query, $verbose);
}

?>