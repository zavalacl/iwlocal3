<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/political_action.php');
	
	$file_id = (int) $_GET['fid'];
	$file_info = getPoliticalActionFile($file_id);
	$isLink = ($file_info['url']) ? true : false;
	
	
	// Edit a Political Action File
	if(isset($_POST['submit'])){
		try {
			
			$required = array('title');
			if($isLink) $required[] = 'url';
			
			$validator = new Validator($required);
			$validator->noFilter('title');
			$validator->noFilter('comment');
			$validator->isValidImageType('image');
			
			if($isLink) $validator->noFilter('url'); else $validator->isValidFileType('file');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				if(editPoliticalActionFile($file_id, $clean['title'], $clean['comment'])){
										
					
					// Replace image?
					if(!empty($_FILES['image']['name'])){
						
						// Image
						$image_extension = getExtension($_FILES['image']['name']);
						$image_filename = uniqid(time()).'.'.$image_extension;
						
						if(move_uploaded_file($_FILES['image']['tmp_name'], $file_paths['political_action_images'].$image_filename)){
							if(editPoliticalActionFileImage($file_id, $image_filename) <= 0){
								$alerts->addAlert('The image could not be replaced.');
							}
						} else {
							$alerts->addAlert('The image could not be uploaded.');
						}
						
					}
					
					
				
					// Link?
					if($isLink){
						
						if(editPoliticalActionFileURL($file_id, formatURI($clean['url']))){
							$alerts->addAlert('The file was successfully edited.', 'success');
							$file_info['url'] = $filtered['url'];
						} else {
							$alerts->addAlert('The file could not be edited.');
						}
						
					// or File
					} else {
						
						// Replace File?
						if(!empty($_FILES['file']['name'])){
					
							$extension = getExtension($_FILES['file']['name']);
							$filename = uniqid(time()).'.'.$extension;
							
							if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['political_action_files'].$filename)){
								if(editPoliticalActionFileFile($file_id, $filename, escapeData($_FILES['file']['name'])) > 0){
									$alerts->addAlert('The file was successfully edited.', 'success');
									
									$file_info['filename'] = $filename;
									$file_info['original_filename'] = $_FILES['file']['name'];
								} else {
									$alerts->addAlert('The file could not be edited.');
								}
							} else {
								$alerts->addAlert('The file could not be uploaded.');
							}
							
						} else {
							$alerts->addAlert('The file was successfully edited.', 'success');
						}
					}
					
					
					// Get updated info
					$file_info = getPoliticalActionFile($file_id);
					
				} else {
					$alerts->addAlert('The file could not be edited.');
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
<title>Admin: Political Action Files | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='political_action_files'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="political_action_files.php">Political Action Files</a></li>
         	<li>Edit Political Action File</li>
         </ul>
               
      	<h1>Admin: Edit Political Action File</h1>
         
         <p>Complete the form below to edit the Political Action file.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>?fid=<?php echo $file_id; ?>" method="post" enctype="multipart/form-data">
<?php
		if($file_info['image']){
?>
					<img src="<?php echo new Image($file_info['image'], 150, 150, 'crop', 'political_action_images'); ?>" alt="" style="float: right;">
<?php
		}
?>
					 
         	<p><label for="title"><span class="required">*</span> Title</label> <input type="text" name="title" id="title" value="<?php echo $file_info['title']; ?>" maxlength="255" /></p>
         	
         	<p><label for="image">Replace Image</label> <input type="file" name="image" id="image" /></p>
<?php
		if($isLink){
?>
            <p><label for="url"><span class="required">*</span> URL</label> <input type="text" name="url" id="url" value="<?php echo $file_info['url']; ?>" maxlength="255" /></p>
<?php
		} else {
?>
				<p>
					<label for="file">Replace File</label> <input type="file" name="file" id="file" /><br />
					<span style="display:inline-block; font-size:.9em;">Current file: <?php echo $file_info['original_filename']; ?> (<?php echo number_format(@filesize($file_paths['files'].$file_info['filename'])/1024, 1); ?>KB)</span>	
				</p>
<?php
		}
?>
            <p><label for="comment">Comment</label> <input type="text" name="comment" id="comment" value="<?php echo $file_info['comment']; ?>" maxlength="255" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Edit File" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>