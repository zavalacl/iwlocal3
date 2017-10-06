<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
		
	if(!empty($_POST['rids'])){
		$rep_ids = $_POST['rids'];
		$position = 1;
		foreach($rep_ids as $rep_id) {
			updateContractorRepPosition($rep_id, $position);
			$position++;
		}
	}
?>