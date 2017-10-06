<?php

	// Function for checking if a user is logged-in
	function loggedIn(){
		@session_start();
		if(isset($_SESSION['user_info']['user_id']))
			return true;
		else
			return false;
	}

	// Function for trimming "index.php" from end of URI
	function getSelf(){
		$self = $_SERVER['PHP_SELF'];
		if(substr($self, -9) === "index.php"){
			$self = substr($self, 0, -9);
		}
		return $self;
	}
	
	// Function for trimming "http://" from beginning of URI
	function formatURI($uri){
		if(substr($uri, 0, 7) === "http://"){
			$uri = substr($uri, 7);
		}
		return $uri;
	}
	
	// Function for placing "http://" on the beginning of URI
	function forceURI($uri){
		if(substr($uri, 0, 7) !== "http://" && substr($uri, 0, 8) !== "https://"){
			$uri = "http://".$uri;
		}
		return $uri;
	}
	
	// Format a US phone number
	function formatPhone($phone){
		$phone = preg_replace("/[^0-9]/", "", $phone);
	
		if(strlen($phone) == 7)
			return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);
		elseif(strlen($phone) == 10)
			return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone);
		elseif (strlen($phone) == 11)
			return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone);
		else
			return $phone;
	}
	
	// Strip phone number down to just numerals
	function cleanPhone($phone){
		return preg_replace("/[^0-9]/", "", $phone);
	}
	
	// Function for getting the extension of a file
	function getExtension($filename){
		return @strtolower(array_pop(explode('.', $filename)));
	}
	
	// Function for escaping and trimming data.
	function escapeData($data) {
		global $mysqli;
        return $mysqli->real_escape_string(trim($data));
	}
	
	// remove spaces in string and make all lowercase
	function smash($text){ 
		return preg_replace('/\s+/', '', strtolower($text));
	}
	
	// Replace multiple, consecutive spaces with a single space
	function smashSpaces($text){ 
		return preg_replace('/\s+/', ' ', $text);
	}
	
	// Ensure a safe link
	function safeLink($string){
		return strtolower(preg_replace('/\s+/', '-', preg_replace("/[^[:alnum:] -]/", "", $string)));
	}
    
    // Remove items with numeric keys from an array
    function removeNumericKeys($array){
        foreach ($array as $key => $value) {
            if (is_int($key)) {
                unset($array[$key]);
            }
        }
        return $array;
    }
	
	// Get the number of weeks in a month
	function numMonthsWeeks($year, $month, $start=0){
		$unix = strtotime("$year-$month-01");
		$numDays = date('t', $unix);
		if($start===0){
			$dayOne = date('w', $unix); // sunday based week 0-6
		} else {
			$dayOne = date('N', $unix); //monday based week 1-7
			$dayOne--; //convert for 0 based weeks
		}
		
		// If day one is not the start of the week then advance to start
		$numDays += $dayOne;
		$numWeeks = ceil($numDays/7);
		return $numWeeks;
	}
	
	// Put contents of file into a variable
	function includeToVar($file, $vars=array()){
		ob_start();
		
		if($vars){
			foreach($vars as $key=>$value){
				$$key = $value;
			}
		}
				
		include($file);
		return ob_get_clean();
	}

?>