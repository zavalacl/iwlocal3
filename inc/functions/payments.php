<?php

function newPayment($user_id, $reference_id, $type, $first_name, $last_name, $email, $phone, $card_type, $card_number, $amount, $verbose=false){
	$user_id = ($user_id) ? $user_id : "NULL";
	$reference_id = ($reference_id) ? $reference_id : "NULL";
	$query = "INSERT INTO `payments` (`user_id`, `reference_id`, `type`, `first_name`, `last_name`, `email`, `phone`, `card_type`, `card_number`, `amount`, `date`) VALUES ($user_id, $reference_id, '$type', '$first_name', '$last_name', '$email', '$phone', '$card_type', '$card_number', '$amount', NOW())";
	return insertQuery($query, $verbose);
}

function setPaymentTransactionId($payment_id, $transaction_id, $verbose=false){
	$query = "UPDATE `payments` SET `transaction_id`='$transaction_id' WHERE `payment_id`=$payment_id LIMIT 1";
	return query($query, $verbose);
}

function getPayments($type=false, $verbose=false){
	$type = ($type) ? "WHERE `type`='$type'" : "";
	$query = "SELECT * FROM `payments` $type ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function getUserPayments($user_id, $type=false, $verbose=false){
	$type = ($type) ? "AND `type`='$type'" : "";
	$query = "SELECT * FROM `payments` WHERE `user_id`=$user_id $type ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function getReferenceIdPayments($reference_id, $type, $verbose=false){
	$query = "SELECT * FROM `payments` WHERE `reference_id`=$reference_id AND `type`='$type' ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function getPayment($payment_id, $verbose=false){
	$query = "SELECT * FROM `payments` WHERE `payment_id`=$payment_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function deletePayment($payment_id, $verbose=false){
	$query = "DELETE FROM `payments` WHERE `payment_id`=$payment_id LIMIT 1";
	return query($query, $verbose);
}

?>