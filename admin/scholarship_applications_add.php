<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/scholarship_applications.php');
	
	
	// Add Application
	if(isset($_POST['submit'])){
		try {
			$required = array('title');
			
			$validator = new Validator($required);
			$validator->noFilter('title');
			$validator->noFilter('description');
			$validator->isInt('active');
			$validator->isValidFileType('file');
				
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				$active = (isset($_POST['active'])) ? 1 : 0;
								
				$application_id = newScholarshipApplication($clean['title'], $clean['description'], $active);
				if($application_id > 0){
					
					$extension = getExtension($_FILES['file']['name']);
					$filename = uniqid(time()).'.'.$extension;
					
					if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['scholarship_applications'].$filename)){
						if(editScholarshipApplicationFile($application_id, $filename, escapeData($_FILES['file']['name'])) > 0){
							header("Location: scholarship_applications.php?na");
							exit;
						} else {
							$alerts->addAlert('The application could not be added.');
							deleteScholarshipApplication($application_id);
						}
					} else {
						$alerts->addAlert('The application could not be uploaded.');
						deleteScholarshipApplication($application_id);
					}
					
				} else {
					$alerts->addAlert('The application could not be added.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Announcements: Scholarship Applications | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='scholarship_applications'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="scholarship_applications.php">Scholarship Applications</a></li>
         	<li>Add Application</li>
         </ul>
               
      	<h1>Admin: Announcements: Scholarship Applications: Add Application</h1>
         
         <p>Complete the form below to add an application.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
         	<p><label for="title"><span class="required">*</span> Title</label> <input type="text" name="title" id="title" value="<?php echo (!empty($_POST['title'])) ? stripslashes($_POST['title']) : ''; ?>" maxlength="255" /></p>
            <p><label for="description">Description</label> <input type="text" name="description" id="description" value="<?php echo (!empty($_POST['description'])) ? stripslashes($_POST['description']) : ''; ?>" maxlength="255" /></p>
         	<p><label for="file">File</label> <input type="file" name="file" id="file" /></p>
            <p><div class="label">Active</div> <input type="checkbox" name="active" id="active" value="1" style="width:auto;border:none;"<?php echo (!isset($_POST['submit']) || isset($_POST['active'])) ? ' checked="checked"' : ''; ?> /> <label for="active" class="plain">Yes</label></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Add Application" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>