<?php

function newDeathNotice($first_name, $last_name, $age, $book_number, $years_member, $visitation, $funeral_service, $burial, $date, $verbose=false){
	$query = "INSERT INTO `death_notices` (`first_name`, `last_name`, `age`, `book_number`, `years_member`, `visitation`, `funeral_service`, `burial`, `date`) VALUES ('$first_name', '$last_name', '$age', '$book_number', '$years_member', '$visitation', '$funeral_service', '$burial', '$date')";
	return insertQuery($query, $verbose);
}

function editDeathNotice($notice_id, $first_name, $last_name, $age, $book_number, $years_member, $visitation, $funeral_service, $burial, $date, $verbose=false){
	$query = "UPDATE `death_notices` SET `first_name`='$first_name', `last_name`='$last_name', `age`='$age', `book_number`='$book_number', `years_member`='$years_member', `visitation`='$visitation', `funeral_service`='$funeral_service', `burial`='$burial', `date`='$date' WHERE `notice_id`=$notice_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getDeathNotice($notice_id, $verbose=false){
	$query = "SELECT * FROM `death_notices` WHERE `notice_id`=$notice_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getLatestDeathNoticeMonth($verbose=false){
	$query = "SELECT MONTH(`date`) FROM `death_notices` ORDER BY `date` DESC LIMIT 1";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return date('n');
}

function getLatestDeathNoticeYear($verbose=false){
	$query = "SELECT YEAR(`date`) FROM `death_notices` ORDER BY `date` DESC LIMIT 1";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return date('Y');
}

function getDeathNotices($year=false, $month=false, $verbose=false){
	$year = ($year) ? "AND YEAR(`date`)=$year" : "";
	$month = ($month) ? "AND MONTH(`date`)=$month" : "";
	$query = "SELECT * FROM `death_notices` WHERE 1 $year $month ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function getDeathNoticeMonths($verbose=false){
	$query = "SELECT YEAR(`date`) as `year`, MONTH(`date`) as `month` FROM `death_notices` GROUP BY `year`, `month` ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function deleteDeathNotice($notice_id, $verbose=false){
	$query = "DELETE FROM `death_notices` WHERE `notice_id`=$notice_id LIMIT 1";
	return query($query, $verbose);
}

?>