<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/projects.php');
	
	
	// Add Project
	if(isset($_POST['submit'])){
		try {
			$required = array('name', 'image_1');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->noFilter('description');
			$validator->isValidImageType('image_1');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				$result = newProject($clean['name'], $clean['description']);
				if($result > 0){
					
					// Upload Images
					for($i=1; $i<=5; $i++){
						if(!empty($_FILES['image_'.$i]['name'])){
							if(valid_image_type($_FILES['image_'.$i]['tmp_name'])){
								$extension = getExtension($_FILES['image_'.$i]['name']);
								$filename = uniqid(time()).'.'.$extension;
								
								if(move_uploaded_file($_FILES['image_'.$i]['tmp_name'], $file_paths['project_images'].$filename)){
									$caption = ($_POST['caption_'.$i]!='Optional Caption') ? escapeData($_POST['caption_'.$i]) : '';
									newProjectImage($result, $filename, $caption);
								}
							}
						}
					}
					
					header("Location: projects.php?np");
					exit;
				} else {
					$alerts->addAlert('The project could not be added.');
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
<title>Admin: Add Project | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='projects'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="projects.php">Projects</a></li>
         	<li>Add Project</li>
         </ul>
               
      	<h1>Admin: Add Project</h1>
         
         <p>Complete the form below to add a new project.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
            <p><label for="name"><span class="required">*</span> Name</label> <input type="text" name="name" id="name" value="<?php echo (!empty($_POST['name'])) ? stripslashes($_POST['name']) : ''; ?>" /></p>
            <p><label for="description">Description</label> <textarea name="description" id="description" cols="40" rows="10" style="width:400px;"><?php echo (!empty($_POST['description'])) ? stripslashes($_POST['description']) : ''; ?></textarea></p>
            <p>
            	<label for="image_1"><span class="required">*</span> Image 1</label> 
               <input type="file" name="image_1" id="image_1" style="width:auto;" />
            	<input type="text" name="caption_1" id="caption_1" value="<?php echo (!empty($_POST['caption_1'])) ? stripslashes($_POST['caption_1']) : 'Optional Caption'; ?>" onfocus="if(this.value=='Optional Caption') this.value='';" onblur="if(this.value=='') this.value='Optional Caption';" />
            </p>
<?php
			for($i=2; $i<=5; $i++){
?>
				<p>
            	<label for="image_<?php echo $i ?>">Image <?php echo $i ?></label> 
               <input type="file" name="image_<?php echo $i ?>" id="image_<?php echo $i ?>" style="width:auto;" /> 
            	<input type="text" name="caption_<?php echo $i ?>" id="caption_<?php echo $i ?>" value="<?php echo (!empty($_POST['caption_'.$i])) ? stripslashes($_POST['caption_'.$i]) : 'Optional Caption'; ?>" onfocus="if(this.value=='Optional Caption') this.value='';" onblur="if(this.value=='') this.value='Optional Caption';" maxlength="128" />
            </p>
<?php	
			}
?>
				<p style="padding-left:90px;font-size:.9em;font-style:italic;">Additional images can be uploaded from the project edit page.</p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Add Project" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>