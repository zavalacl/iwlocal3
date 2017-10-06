<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/officers.php');
		
	if(!empty($_POST['oids'])){
		$officer_ids = $_POST['oids'];
		$position = 1;
		foreach($officer_ids as $officer_id) {
			updateOfficerPosition($officer_id, $position);
			$position++;
		}
	}
?>