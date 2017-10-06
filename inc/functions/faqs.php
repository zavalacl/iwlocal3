<?php

function newFaq($question, $answer, $verbose=false){
	$position = getMaxFaqPosition($verbose)+1;
	$query = "INSERT INTO `faqs` (`question`, `answer`, `position`) VALUES ('$question', '$answer', $position)";
	return insertQuery($query, $verbose);
}

function editFaq($faq_id, $question, $answer, $verbose=false){
	$query = "UPDATE `faqs` SET `question`='$question', `answer`='$answer' WHERE `faq_id`=$faq_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateFaqPosition($faq_id, $position, $verbose=false){
	$query = "UPDATE `faqs` SET `position`=$position WHERE `faq_id`=$faq_id LIMIT 1";
	return query($query, $verbose);
}

function getFaqs($verbose=false){
	$query = "SELECT * FROM `faqs` ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}

function getFaq($faq_id, $verbose=false){
	$query = "SELECT * FROM `faqs` WHERE `faq_id`=$faq_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getMaxFaqPosition($verbose=false){
	$query = "SELECT MAX(`position`) FROM `faqs`";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return $result;
}

function deleteFaq($faq_id, $verbose=false){
	$query = "DELETE FROM `faqs` WHERE `faq_id`=$faq_id LIMIT 1";
	return query($query, $verbose);
}

?>