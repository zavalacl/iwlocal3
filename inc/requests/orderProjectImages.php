<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/projects.php');
		
	if(!empty($_POST['iids'])){
		$image_ids = $_POST['iids'];
		$position = 1;
		foreach($image_ids as $image_id) {
			updateProjectImagePosition($image_id, $position);
			$position++;
		}
	}
?>