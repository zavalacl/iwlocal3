<?php
	require('config.php');
	require("authenticate.php");
	require("functions/stewards_reports.php");
		
	if(!empty($_POST['lid'])){
		$location_id = (int) $_POST['lid'];
		$location_info = getProjectLocation($location_id);	
		if($location_info > 0){
		
			$print  = '{';
			$print .= '"address": "'.$location_info['address'].'", ';
			$print .= '"city": "'.$location_info['city'].'", ';
			$print .= '"county": "'.$location_info['county'].'"';
			$print .= '}';
			
			echo $print;
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
?>