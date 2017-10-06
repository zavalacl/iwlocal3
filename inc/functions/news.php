<?php

function newNews($news, $date, $verbose=false){
	$query = "INSERT INTO `news` (`news`, `date`) VALUES ('$news', '$date')";
	return insertQuery($query, $verbose);
}

function editNews($news_id, $news, $date, $verbose=false){
	$query = "UPDATE `news` SET `news`='$news', `date`='$date' WHERE `news_id`=$news_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getNews($news_id, $verbose=false){
	$query = "SELECT * FROM `news` WHERE `news_id`=$news_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getAllNews($verbose=false){
	$query = "SELECT * FROM `news` ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function deleteNews($news_id, $verbose=false){
	$query = "DELETE FROM `news` WHERE `news_id`=$news_id LIMIT 1";
	return query($query, $verbose);
}

?>