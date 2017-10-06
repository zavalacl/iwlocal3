<?php
	
/* Contractor Categories */
function newContractorCategory($category, $verbose=false){
	$query = "INSERT INTO `contractors_categories` (`category`, `date_added`) VALUES ('$category', NOW())";
	return insertQuery($query, $verbose);
}

function editContractorCategory($category_id, $category, $verbose=false){
	$query = "UPDATE `contractors_categories` SET `category`='$category' WHERE `category_id`=$category_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getContractorCategory($category_id, $verbose=false){
	$query = "SELECT * FROM `contractors_categories` WHERE `category_id`=$category_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getContractorCategories($verbose=false){
	$query = "SELECT * FROM `contractors_categories` ORDER BY `category` ASC";
	return selectArrayQuery($query, $verbose);
}

function getContractorCategoriesByFirstLetter($letter, $verbose=false){
	$query = "SELECT * FROM `contractors_categories` WHERE UPPER(SUBSTRING(`category`, 1, 1))='$letter' ORDER BY `category` ASC";
	return selectArrayQuery($query, $verbose);
}

function getContractorCategoryFirstLetters($verbose=false){
	$return = array();
	
	$query = "SELECT DISTINCT(UPPER(SUBSTRING(`category`, 1, 1))) AS `letter` FROM `contractors_categories` ORDER BY `letter` ASC";
	$results = selectArrayQuery($query, $verbose);
	if($results > 0){
		foreach($results as $result){
			$return[] = $result['letter'];
		}
	}
	
	return $return;
}

function deleteContractorCategory($category_id, $verbose=false){
	$query = "DELETE FROM`contractors_categories` WHERE `category_id`=$category_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0){
		 deleteContractorCategoryMapByCategory($category_id, $verbose);
	}
	
	return $result;
}




/* Contractors */
function newContractor($name, $address, $address_2, $city, $state, $zip, $phone, $fax, $url, $specialties, $verbose=false){
	$query = "INSERT INTO `contractors` (`name`, `address`, `address_2`, `city`, `state`, `zip`, `phone`, `fax`, `url`, `specialties`, `date_added`) VALUES ('$name', '$address', '$address_2', '$city', '$state', '$zip', '$phone', '$fax', '$url', '$specialties', NOW())";
	return insertQuery($query, $verbose);
}

function editContractor($contractor_id, $name, $address, $address_2, $city, $state, $zip, $phone, $fax, $url, $specialties, $verbose=false){
	$query = "UPDATE `contractors` SET `name`='$name', `address`='$address', `address_2`='$address_2', `city`='$city', `state`='$state', `zip`='$zip', `phone`='$phone', `fax`='$fax', `url`='$url', `specialties`='$specialties' WHERE `contractor_id`=$contractor_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getContractor($contractor_id, $verbose=false){
	$query = "SELECT * FROM `contractors` WHERE `contractor_id`=$contractor_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getContractorByName($name, $verbose=false){
	$query = "SELECT * FROM `contractors` WHERE UPPER(`name`)='" . strtoupper($name) . "' LIMIT 1";
	return selectQuery($query, $verbose);
}


function getContractors($verbose=false){
	$query = "SELECT * FROM `contractors` ORDER BY `name` ASC";
	return selectArrayQuery($query, $verbose);
}

function getContractorsInCategory($category_id, $verbose=false){
	$query = "
		SELECT *
		FROM `contractors`, `contractors_categories_map`
		WHERE `contractors`.`contractor_id` = `contractors_categories_map`.`contractor_id`
		AND `contractors_categories_map`.`category_id` = $category_id
		ORDER BY `contractors`.`name` ASC
	";
	return selectArrayQuery($query, $verbose);
}

function deleteContractor($contractor_id, $verbose=false){
	$query = "DELETE FROM`contractors` WHERE `contractor_id`=$contractor_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0){
		deleteContractorCategoryMapByContractor($contractor_id, $verbose);
	}
	
	return $result;
}




/* Contractor Categories Map */
function newContractorCategoryMap($contractor_id, $category_id, $verbose=false){
	$query = "INSERT INTO `contractors_categories_map` (`contractor_id`, `category_id`) VALUES ($contractor_id, $category_id)";
	return insertQuery($query, $verbose);
}

function contractorIsMappedToCategory($contractor_id, $category_id, $verbose=false){
	$query = "SELECT * FROM `contractors_categories_map` WHERE `contractor_id`=$contractor_id AND `category_id`=$category_id LIMIT 1";
	$result = selectQuery($query, $verbose);
	
	if($result > 0){
		return true;
	} else {
		return false;
	}
}

function getContractorCategoryMapByContractor($contractor_id, $category_ids_only=false, $verbose=false){
	$query = "SELECT * FROM `contractors_categories_map` WHERE `contractor_id`=$contractor_id";
	$results = selectArrayQuery($query, $verbose);
	
	if($results > 0 && $category_ids_only){
		$return = array();
		
		foreach($results as $result){
			$return[] = $result['category_id'];
		}
		
		return $return;
	}
	
	return $results;
}

function getContractorCategoryMapByCategory($category_id, $contractor_ids_only=false, $verbose=false){
	$query = "SELECT * FROM `contractors_categories_map` WHERE `category_id`=$category_id";
	$results = selectArrayQuery($query, $verbose);
	
	if($results > 0 && $contractor_ids_only){
		$return = array();
		
		foreach($results as $result){
			$return[] = $result['contractor_id'];
		}
		
		return $return;
	}
	
	return $results;
}

function deleteContractorCategoryMapByContractor($contractor_id, $verbose=false){
	$query = "DELETE FROM `contractors_categories_map` WHERE `contractor_id`=$contractor_id";
	return query($query, $verbose);
}

function deleteContractorCategoryMapByCategory($category_id, $verbose=false){
	$query = "DELETE FROM `contractors_categories_map` WHERE `category_id`=$category_id";
	return query($query, $verbose);
}
	
	
	
	
/* Featured Contractors */
function newFeaturedContractor($contractor, $description, $date, $verbose=false){
	$query = "INSERT INTO `contractors_featured` (`contractor`, `description`, `date`) VALUES ('$contractor', '$description', '$date')";
	return insertQuery($query, $verbose);
}

function editFeaturedContractor($feature_id, $contractor, $description, $date, $verbose=false){
	$query = "UPDATE `contractors_featured` SET `contractor`='$contractor', `description`='$description', `date`='$date' WHERE `feature_id`=$feature_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateFeaturedContractorImage($feature_id, $image_filename, $verbose=false){
	$query = "UPDATE `contractors_featured` SET `image_filename`='$image_filename' WHERE `feature_id`=$feature_id LIMIT 1";
	return query($query, $verbose);
}

function deleteFeaturedContractorImage($feature_id, $verbose=false){
	$query = "UPDATE `contractors_featured` SET `image_filename`=NULL WHERE `feature_id`=$feature_id LIMIT 1";
	return query($query, $verbose);
}

function getFeaturedContractor($feature_id, $verbose=false){
	$query = "SELECT * FROM `contractors_featured` WHERE `feature_id`=$feature_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getCurrentFeaturedContractor($verbose=false){
	$query = "SELECT * FROM `contractors_featured` ORDER BY `date` DESC LIMIT 1";
	return selectQuery($query, $verbose);
}

function getFeaturedContractors($verbose=false){
	$query = "SELECT * FROM `contractors_featured` ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function deleteFeaturedContractor($feature_id, $verbose=false){
	$query = "DELETE FROM `contractors_featured` WHERE `feature_id`=$feature_id LIMIT 1";
	return query($query, $verbose);
}


/* Contractor Documents */
function newContractorDocument($title, $filename, $original_filename, $description, $verbose=false){
	$query = "INSERT INTO `contractors_documents` (`title`, `filename`, `original_filename`, `description`, `date`) VALUES ('$title', '$filename', '$original_filename', '$description', NOW())";
	return insertQuery($query, $verbose);
}

function editContractorDocument($document_id, $title, $description, $verbose=false){
	$query = "UPDATE `contractors_documents` SET `title`='$title', `description`='$description' WHERE `document_id`=$document_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function editContractorDocumentFile($document_id, $filename, $original_filename, $verbose=false){
	$query = "UPDATE `contractors_documents` SET `filename`='$filename', `original_filename`='$original_filename' WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function getContractorDocuments($verbose=false){
	$query = "SELECT * FROM `contractors_documents` ORDER BY `title` ASC";
	return selectArrayQuery($query, $verboses);
}

function getContractorDocument($document_id, $verbose=false){
	$query = "SELECT * FROM `contractors_documents` WHERE `document_id`=$document_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function incrementContractorDocumentDownloads($document_id, $verbose=false){
	$query = "UPDATE `contractors_documents` SET `downloads`=`downloads`+1 WHERE `document_id`=$document_id LIMIT 1";
	return query($query, $verbose);
}

function deleteContractorDocument($document_id, $verbose=false){
	$document_info = getContractorDocument($document_id, $verbose);
	$query = "DELETE FROM `contractors_documents` WHERE `document_id`=$document_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		global $file_paths;
		@unlink($file_paths['contractor_documents'].$document_info['filename']);
	}
	return $result;
}




/* Contractor Territories */
function getContractorRepTerritories($verbose=false){
	$query = "SELECT * FROM `contractors_territories` ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}




/* Contractor Reps */
function newContractorRep($territory_id, $first_name, $last_name, $title, $office, $fax, $cell, $email, $note, $verbose=false){
	$position = getMaxContractorRepPosition($category_id, $verbose) + 1;
	
	$query = "INSERT INTO `contractors_reps` (`territory_id`, `first_name`, `last_name`, `title`, `office`, `fax`, `cell`, `email`, `note`, `position`) VALUES ($territory_id, '$first_name', '$last_name', '$title', '$office', '$fax', '$cell', '$email', '$note', $position)";
	return insertQuery($query, $verbose);
}

function editContractorRep($rep_id, $territory_id, $first_name, $last_name, $title, $office, $fax, $cell, $email, $note, $verbose=false){
	$query = "UPDATE `contractors_reps` SET `territory_id`=$territory_id, `first_name`='$first_name', `last_name`='$last_name', `title`='$title', `office`='$office', `fax`='$fax', `cell`='$cell', `email`='$email', `note`='$note' WHERE `rep_id`=$rep_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateContractorRepPosition($rep_id, $position, $verbose=false){
	$query = "UPDATE `contractors_reps` SET `position`=$position WHERE `rep_id`=$rep_id LIMIT 1";
	return query($query, $verbose);
}

function getContractorRep($rep_id, $verbose=false){
	$query = "SELECT * FROM `contractors_reps` WHERE `rep_id`=$rep_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getContractorReps($territory_id, $verbose=false){
	$query = "SELECT * FROM `contractors_reps` WHERE `territory_id`=$territory_id ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}

function getMaxContractorRepPosition($territory_id, $verbose=false){
	$query = "SELECT MAX(`position`) FROM `contractors_reps` WHERE `territory_id`=$territory_id";
	$result = selectQuery($query, $verbose);
	if($result > 0){
		return $result[0];
	} else {
		return 0;
	}
}

function deleteContractorRep($rep_id, $verbose=false){
	$query = "DELETE FROM `contractors_reps` WHERE `rep_id`=$rep_id LIMIT 1";
	return query($query, $verbose);
}

?>