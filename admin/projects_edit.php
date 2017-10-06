<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/projects.php');
	
	$project_id = (int) $_GET['pid'];
	
	
	// Edit Project
	if(isset($_POST['submit'])){
		try {
			$required = array('name');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->noFilter('description');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editProject($project_id, $clean['name'], $clean['description'])){
					$alerts->addAlert('The project was successfully edited.');
					
					// Upload Images
					for($i=1; $i<=3; $i++){
						if(!empty($_FILES['image_'.$i]['name'])){
							if(valid_image_type($_FILES['image_'.$i]['tmp_name'])){
								$extension = getExtension($_FILES['image_'.$i]['name']);
								$filename = uniqid(time()).'.'.$extension;
								
								if(move_uploaded_file($_FILES['image_'.$i]['tmp_name'], $file_paths['project_images'].$filename)){
									$caption = ($_POST['caption_'.$i]!='Optional Caption') ? escapeData($_POST['caption_'.$i]) : '';
									if(newProjectImage($project_id, $filename, $caption) <= 0){
										$alerts->addAlert("Image $i could not be added.");
									}
								} else {
									$alerts->addAlert("Image $i could not be uploaded.");
								}
							} else {
								$alerts->addAlert("Image $i was not a valid image.");
							}
						}
					}
					
					unset($_POST);
					
				} else {
					$alerts->addAlert('The project could not be edited.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Delete a Project Image
	if(!empty($_GET['d1'])){
		$delete_id = escapeData($_GET['d1']);
		if(deleteProjectImage($delete_id) > 0){
			$alerts->addAlert('The image was successfully deleted.');
		} else {
			$alerts->addAlert('The image could not be deleted.');
		}
	}
	
	// Get Project Info
	$project_info = getProject($project_id);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Edit Project | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<script type="text/javascript" src="/js/jquery-ui-1.8rc3.custom.min.js"></script>
<script type="text/javascript">
$(function() {
	$("#images").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize");
			$.post("/inc/requests/orderProjectImages.php", order);
		}
	});
});
</script>
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
         	<li>Edit Project</li>
         </ul>
               
      	<h1>Admin: Edit Project</h1>
         
         <div class="admin_left">
            <p>Complete the form below to edit the project's information.</p>
            
            <?php if($alerts->hasAlerts()) echo $alerts; ?>
            
            <form action="<?php echo getSelf(); ?>?pid=<?php echo $project_id; ?>" method="post" enctype="multipart/form-data">
               <p><label for="name"><span class="required">*</span> Name</label> <input type="text" name="name" id="name" value="<?php echo $project_info['name']; ?>" /></p>
               <p><label for="description">Description</label> <textarea name="description" id="description" cols="40" rows="12" style="width:395px;"><?php echo $project_info['description']; ?></textarea></p>
               
               <h2>Add Images</h2>
<?php
			for($i=1; $i<=3; $i++){
?>
					<p>
            	<label for="image_<?php echo $i ?>">Image <?php echo $i ?></label> 
               <input type="file" name="image_<?php echo $i ?>" id="image_<?php echo $i ?>" style="width:auto;" /><br />
            	<input type="text" name="caption_<?php echo $i ?>" id="caption_<?php echo $i ?>" value="<?php echo (!empty($_POST['caption_'.$i])) ? stripslashes($_POST['caption_'.$i]) : 'Optional Caption'; ?>" onfocus="if(this.value=='Optional Caption') this.value='';" onblur="if(this.value=='') this.value='Optional Caption';" style="margin:3px 0 0 90px;" maxlength="128" />
            	</p>
<?php	
			}
?>
               <p class="submit"><input type="submit" name="submit" id="submit" value="Edit Project" /></p>
            </form> 
         </div><!-- div.admin_left -->
         
         <div class="admin_right">
         	<h2>Project Images</h2>
               
            <p>Drag and drop images to reorder</p>
            
            <div id="images">
<?php
      $images = getProjectImages($project_id);
      if($images > 0){
         foreach($images as $image){
?>
               <div class="image row" id="iids_<?php echo $image['image_id']; ?>" style="cursor:move;">
                  <div class="img">
                     <img src="<?php echo new Image($image['filename'],80,80,'fit','project_images'); ?>" alt="" />
                  </div>
                  <div class="info">
                     <a href="projects_edit_image.php?iid=<?php echo $image['image_id']; ?>">Edit Caption</a><br />
                     <a href="javascript:confirm_deletion(1,<?php echo $image['image_id']; ?>,'<?php echo getSelf(); ?>?pid=<?php echo $project_id; ?>','image',1);">Delete Image</a>
                  </div>
               </div>
<?php	
         }
      }
?>
            </div><!-- div#images -->
           
         </div><!-- div.admin_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>