<?php

function valid_email($email){
	if (preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/", stripslashes(trim($email))))
		return TRUE;
	else
		return FALSE;
}

function valid_username($string){
	if(preg_match("/^[a-z0-9_-]{3,32}$/", $string) && !is_numeric($string))
		return TRUE;
	else
		return FALSE;
}

function valid_date($date){
	if(empty($date))
		return false;
	list($year, $month, $day) = explode("-", $date);
	if(!is_numeric($year) || !is_numeric($month) || !is_numeric($day))
		return false;
	if($month < 1 || $month > 12)
		return false;
	if($day < 1 || $day > 31)
		return false;
	if($year < 1900 || $year > 3000)
		return false;
		
	return true;
}

function valid_input($input){
	if(!empty($input))
		return TRUE;
	else
		return FALSE;
}

function valid_zip($zip){
	if(empty($zip) || !is_numeric($zip)){
		return FALSE;
	}else{
		$query = "SELECT `zipcode` FROM `locations` WHERE `zipcode`='$zip' LIMIT 1";
		$result = select_query($query);
		if($result > 0)
			return TRUE;
		else
			return FALSE;
	}
}

function valid_image_type($sourceFile){
	$size = @getimagesize($sourceFile);
	$fp = fopen($sourceFile, "rb");
	if ($size && $fp) {
		$mime = $size[2];
		//1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 13 = SWC
		if($mime == '1' || $mime == '2' || $mime == '3'){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

function valid_image($sourceFile){
	$return = TRUE;
	
	if(file_exists($sourceFile)){
		$image_info = getimagesize($sourceFile);
		
		switch ($image_info['mime']) {
			case 'image/gif':
				if (!(imagetypes() && IMG_GIF)) {
					$return = FALSE;
				} 
				break;
			case 'image/jpeg':
				if (!(imagetypes() && IMG_JPG)) {
					$return = FALSE;
				}
				break;
			case 'image/png':
				if (!(imagetypes() && IMG_PNG)) {
					$return = FALSE;
				}
				break;
			case 'image/jpg':
				if (!(imagetypes() && IMG_JPG)) {
					$return = FALSE;
				}
				break;
			default:
				$return = FALSE;
		}
	} else {
		$return = FALSE;
	}
	return $return;
}

?>