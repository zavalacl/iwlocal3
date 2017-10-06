<?php
	require('config.php');
	require("authenticate.php");
	require("functions/stewards_reports.php");
		
	if(!empty($_POST['pid'])){
		$project_id = (int) $_POST['pid'];
		$project_info = getProject($project_id);	
		if($project_info > 0){
					
			$print  = '{';
			$print .= '"address": "'.$project_info['address'].'", ';
			$print .= '"city": "'.$project_info['city'].'", ';
			$print .= '"county": "'.$project_info['county'].'", ';
			$print .= '"funding": "'.$project_info['funding'].'"';
			$print .= '}';
			
			echo $print;
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
?>