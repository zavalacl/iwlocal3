<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/political_action.php');
	
	
	// Add Political Action File
	if(isset($_POST['submit'])){
		try {
			$required = array('title');
			
			$validator = new Validator($required);
			$validator->noFilter('title');
			$validator->noFilter('comment');
			$validator->isValidImageType('image');
			
			if(!empty($_POST['url']) || !empty($_FILES['file']['name'])){
			
				$isLink = (!empty($_POST['url'])) ? true : false;
				
				if($isLink) $validator->noFilter('url'); else $validator->isValidFileType('file');
				
				$filtered = $validator->validateInput();
				$errors = $validator->getErrors();
				
				if(!$errors){
					$clean = array_map('escapeData', $filtered);
									
					$file_id = newPoliticalActionFile($clean['title'], $clean['comment']);
					if($file_id > 0){
						
						
						// Image
						if( ! empty($_FILES['image']['name'])){
							$image_extension = getExtension($_FILES['image']['name']);
							$image_filename = uniqid(time()).'.'.$image_extension;
							
							if(move_uploaded_file($_FILES['image']['tmp_name'], $file_paths['political_action_images'].$image_filename)){
								if(editPoliticalActionFileImage($file_id, $image_filename) <= 0){
									//$alerts->addAlert('The image could not be added.');
								}
							} else {
								//$alerts->addAlert('The image could not be uploaded.');
							}
						}
						
					
						// Adding a Link?
						if($isLink){
							
							if(editPoliticalActionFileURL($file_id, formatURI($clean['url']))){
								header("Location: political_action_files.php?nf");
								exit;
							} else {
								$alerts->addAlert('The file could not be added.');
								deletePoliticalActionFile($file_id);
							}
							
						// or Uploading a Political Action File
						} else {
						
							$extension = getExtension($_FILES['file']['name']);
							$filename = uniqid(time()).'.'.$extension;
							
							//echo $_FILES['file']['tmp_name'];
							//echo '<br>';
							//echo $file_paths['political_action_files'].$filename;
							//exit;
							
							if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['political_action_files'].$filename)){
								if(editPoliticalActionFileFile($file_id, $filename, escapeData($_FILES['file']['name'])) > 0){
									
									header("Location: political_action_files.php?nf");
									exit;
								
								} else {
									$alerts->addAlert('The file could not be added.');
									deletePoliticalActionFile($file_id);
								}
							} else {
								$alerts->addAlert('The file could not be uploaded.');
								deletePoliticalActionFile($file_id);
							}
						}
					} else {
						$alerts->addAlert('The file could not be added.');
					}
				} else {
					$alerts->addAlerts($errors);
				}
			} else {
				$alerts->addAlert('You must either select a file to upload or enter a URL.');
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
         	<li>Add Political Action File</li>
         </ul>
               
      	<h1>Admin: Add Political Action File</h1>
         
         <p>Complete the form below to add a Political Action file. You must either select a file to upload or add a URL.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
         	
         	<p><label for="title"><span class="required">*</span> Title</label> <input type="text" name="title" id="title" value="<?php echo (!empty($_POST['title'])) ? stripslashes($_POST['title']) : ''; ?>" maxlength="255" /></p>
         	
         	<p><label for="image">Image</i></label> <input type="file" name="image" id="image" /></p>
         	
         	<hr>
         	
         	<p><label for="file">File</label> <input type="file" name="file" id="file" /></p>
          
          <p style="padding-left:90px;">or</p>
          
          <p><label for="url">URL</label> <input type="text" name="url" id="url" value="<?php echo (!empty($_POST['url'])) ? stripslashes($_POST['url']) : ''; ?>" maxlength="255" /></p>
          
          <hr>
          
          <p><label for="comment">Comment</label> <input type="text" name="comment" id="comment" value="<?php echo (!empty($_POST['comment'])) ? stripslashes($_POST['comment']) : ''; ?>" maxlength="255" /></p>
          
          <p class="submit"><input type="submit" name="submit" id="submit" value="Add File" /></p>
         
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>