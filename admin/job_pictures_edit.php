<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/announcements.php');
	
	$document_id = (int) $_GET['did'];
	$document_info = getJobPictureLink($document_id);
	
	
	// Edit a Document
	if(isset($_POST['submit'])){
		try {
			
			$required = array('title');
			
			$validator = new Validator($required);
			$validator->noFilter('title');
			if(!empty($_FILES['file']['name'])) $validator->isValidFileType('file');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				if(editJobPictureLink($document_id, $clean['title'])){
					
					$document_info['title'] = $filtered['title'];
					
					// Replace File?
					if(!empty($_FILES['file']['name'])){
				
						$extension = getExtension($_FILES['file']['name']);
						$filename = uniqid(time()).'.'.$extension;
						
						if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['job_pictures'].$filename)){
							if(editJobPictureLinkFile($document_id, $filename, escapeData($_FILES['file']['name'])) > 0){
								$alerts->addAlert('The document was successfully edited.', 'success');
								$document_info['filename'] = $filename;
								$document_info['original_filename'] = $_FILES['file']['name'];
							} else {
								$alerts->addAlert('The document could not be edited.');
							}
						} else {
							$alerts->addAlert('The document could not be uploaded.');
						}
					} else {
						$alerts->addAlert('The document was successfully edited.');
					}
				} else {
					$alerts->addAlert('The document could not be edited.');
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
<title>Admin: Announcements: Information Links | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='job_pictures'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="job_pictures.php">Job Pictures</a></li>
         	<li>Edit Document</li>
         </ul>
               
      	<h1>Admin: Announcements: Job Pictures: Edit Document</h1>
         
         <p>Complete the form below to edit a document in Job Pictures.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>?did=<?php echo $document_id; ?>" method="post" enctype="multipart/form-data">
         	<p><label for="title"><span class="required">*</span> Title</label> <input type="text" name="title" id="title" value="<?php echo $document_info['title']; ?>" maxlength="255" /></p>
				<p><label for="file">Replace File</label> <input type="file" name="file" id="file" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Edit Document" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>