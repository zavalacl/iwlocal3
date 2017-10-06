<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/faqs.php');
		
	if(!empty($_POST['fids'])){
		$faq_ids = $_POST['fids'];
		$position = 1;
		foreach($faq_ids as $faq_id) {
			updateFaqPosition($faq_id, $position);
			$position++;
		}
	}
?>