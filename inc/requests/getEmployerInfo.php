<?php
	require('config.php');
	require("authenticate.php");
	require("functions/stewards_reports.php");
		
	if(!empty($_POST['eid'])){
		$employer_id = (int) $_POST['eid'];
		$employer_info = getEmployer($employer_id);	
		if($employer_info > 0){
					
			$print  = '{';
			$print .= '"paid_through_date": "'.$employer_info['paid_through'].'", ';
			$print .= '"paid_through_year": "'.date('Y', strtotime($employer_info['paid_through'])).'", ';
			$print .= '"paid_through_month": "'.date('n', strtotime($employer_info['paid_through'])).'", ';
			$print .= '"paid_through_day": "'.date('j', strtotime($employer_info['paid_through'])).'"';
			$print .= '}';
			
			echo $print;
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
?>