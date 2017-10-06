<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/projects.php');
		
	if(!empty($_POST['pids'])){
		$project_ids = $_POST['pids'];
		$position = 1;
		foreach($project_ids as $project_id) {
			updateProjectPosition($project_id, $position);
			$position++;
		}
	}
?>