<?php

/* Projects */
function newProject($name, $description, $verbose=false){
	$position = getMaxProjectPosition($verbose)+1;
	$query = "INSERT INTO `projects` (`name`, `description`, `position`) VALUES ('$name', '$description', $position)";
	return insertQuery($query, $verbose);
}

function editProject($project_id, $name, $description, $verbose=false){
	$query = "UPDATE `projects` SET `name`='$name', `description`='$description' WHERE `project_id`=$project_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateProjectPosition($project_id, $position, $verbose=false){
	$query = "UPDATE `projects` SET `position`=$position WHERE `project_id`=$project_id LIMIT 1";
	return query($query, $verbose);
}

function getProject($project_id, $verbose=false){
	$query = "SELECT * FROM `projects` WHERE `project_id`=$project_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getProjects($verbose=false){
	$query = "SELECT * FROM `projects` ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}

function getMaxProjectPosition($verbose=false){
	$query = "SELECT MAX(`position`) FROM `projects`";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return $result;
}

function deleteProject($project_id, $verbose=false){
	$query = "DELETE FROM `projects` WHERE `project_id`=$project_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		deleteProjectImages($project_id, $verbose);
	}
	return $result;
}


/* Project Images */
function newProjectImage($project_id, $filename, $caption, $verbose=false){
	$position = getMaxProjectImagePosition($project_id, $verbose)+1;
	$query = "INSERT INTO `projects_images` (`project_id`, `filename`, `caption`, `position`) VALUES ($project_id, '$filename', '$caption', $position)";
	return insertQuery($query, $verbose);
}

function editProjectImageCaption($image_id, $caption, $verbose=false){
	$query = "UPDATE `projects_images` SET `caption`='$caption' WHERE `image_id`=$image_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateProjectImagePosition($image_id, $position, $verbose=false){
	$query = "UPDATE `projects_images` SET `position`=$position WHERE `image_id`=$image_id LIMIT 1";
	return query($query, $verbose);
}

function getProjectImage($image_id, $verbose=false){
	$query = "SELECT * FROM `projects_images` WHERE `image_id`=$image_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getFirstProjectImage($project_id, $verbose=false){
	$query = "SELECT * FROM `projects_images` WHERE `project_id`=$project_id ORDER BY `position` ASC LIMIT 1";
	return selectQuery($query, $verbose);
}

function getProjectImages($project_id, $verbose=false){
	$query = "SELECT * FROM `projects_images` WHERE `project_id`=$project_id ORDER BY `position` ASC";
	return selectArrayQuery($query, $verbose);
}

function getMaxProjectImagePosition($project_id, $verbose=false){
	$query = "SELECT MAX(`position`) FROM `projects_images` WHERE `project_id`=$project_id";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return $result;
}

function deleteProjectImage($image_id, $verbose=false){
	$query = "DELETE FROM `projects_images` WHERE `image_id`=$image_id LIMIT 1";
	return query($query, $verbose);
}

function deleteProjectImages($project_id, $verbose=false){
	$query = "DELETE FROM `projects_images` WHERE `project_id`=$project_id";
	return query($query, $verbose);
}

?>