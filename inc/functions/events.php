<?php

/* Events */
function newEvent($event, $date, $email, $description, $times, $location, $registration=0, $price=false, $verbose=false){
	$price = ($price) ? "'$price'" : "NULL";
	$query = "INSERT INTO `events` (`event`, `date`, `email`, `description`, `times`, `location`, `registration`, `price`) VALUES ('$event', '$date', '$email', '$description', '$times', '$location', $registration, $price)";
	return insertQuery($query, $verbose);
}

function editEvent($event_id, $event, $date, $email, $description, $times, $location, $registration, $price=false, $verbose=false){
	$price = ($price) ? "'$price'" : "NULL";
	$query = "UPDATE `events` SET `event`='$event', `date`='$date', `email`='$email', `description`='$description', `times`='$times', `location`='$location', `registration`=$registration, `price`=$price WHERE `event_id`=$event_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getEvent($event_id, $verbose=false){
	$query = "SELECT * FROM `events` WHERE `event_id`=$event_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getUpcomingEvents($verbose=false){
	$query = "SELECT * FROM `events` WHERE `date` >= CURDATE() ORDER BY `date` ASC";
	return selectArrayQuery($query, $verbose);
}

function getPastEvents($verbose=false){
	$query = "SELECT * FROM `events` WHERE `date` < CURDATE() ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function getMonthsEvents($month, $year, $verbose=false){
	$query = "SELECT * FROM `events` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month ORDER BY `date` ASC";
	return selectArrayQuery($query, $verbose);
}

function getMonthsEventsArray($year, $month, $verbose=false){
	$query = "SELECT *, DAY(`date`) AS `day` FROM `events` WHERE YEAR(`date`)=$year AND MONTH(`date`)=$month ORDER BY `date` ASC";
	$results = selectArrayQuery($query, $verbose);
	if($results > 0){
		$return = array();
		foreach($results as $result){
			$return[$result['day']][] = $result;
		}
		return $return;
	} else {
		return array();
	}
}

function deleteEvent($event_id, $verbose=false){
	$query = "DELETE FROM `events` WHERE `event_id`=$event_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		deleteEventRegistrations($event_id, $verbose);
	}
	return $result;
}


/* Event Registrations */
function newEventRegistration($event_id, $user_id, $first_name, $last_name, $email, $address, $address_2, $city, $state, $zip, $phone, $comments, $verbose=false){
	$query = "INSERT INTO `events_registrations` (`event_id`, `user_id`, `first_name`, `last_name`, `email`, `address`, `address_2`, `city`, `state`, `zip`, `phone`, `comments`, `date`) VALUES ($event_id, $user_id, '$first_name', '$last_name', '$email', '$address', '$address_2', '$city', '$state', '$zip', '$phone', '$comments', NOW())";
	return insertQuery($query, $verbose);
}

function setEventRegistrationPayment($registration_id, $payment_id, $amount, $verbose=false){
	$query = "UPDATE `events_registrations` SET `payment_id`=$payment_id, `amount_paid`='$amount' WHERE `registration_id`=$registration_id LIMIT 1";
	return query($query, $verbose);
}

function getEventRegistration($registration_id, $verbose=false){
	$query = "SELECT * FROM `events_registrations` WHERE `registration_id`=$registration_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getEventRegistrations($event_id, $verbose=false){
	$query = "SELECT `registration_id`, `user_id`, `first_name`, `last_name`, `email`, `city`, `state`, `phone`, `date`, `amount_paid` FROM `events_registrations` WHERE `event_id`=$event_id ORDER BY `last_name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteEventRegistration($registration_id, $verbose=false){
	$query = "DELETE FROM `events_registrations` WHERE `registration_id`=$registration_id LIMIT 1";
	return query($query, $verbose);
}

function deleteEventRegistrations($event_id, $verbose=false){
	$query = "DELETE FROM `events_registration` WHERE `event_id`=$event_id";
	return query($query, $verbose);
}

?>