<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/announcements.php');
	
	$link_id = (int) $_GET['lid'];
	$link_info = getLink($link_id);
	$isLink = ($link_info['url']) ? true : false;
	
	
	// Edit a Link
	if(isset($_POST['submit'])){
		try {
			
			$required = array('title');
			if($isLink) $required[] = 'url';
			
			$validator = new Validator($required);
			$validator->noFilter('title');
			
			if($isLink) $validator->noFilter('url'); else $validator->isValidFileType('file');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				if(editLink($link_id, $clean['title'])){
					
					$link_info['title'] = $filtered['title'];
				
					// Link?
					if($isLink){
						
						if(editLinkURL($link_id, formatURI($clean['url']))){
							$alerts->addAlert('The link was successfully edited.', 'success');
							$link_info['url'] = $filtered['url'];
						} else {
							$alerts->addAlert('The link could not be edited.');
						}
						
					// or Document
					} else {
						
						// Replace File?
						if(!empty($_FILES['file']['name'])){
					
							$extension = getExtension($_FILES['file']['name']);
							$filename = uniqid(time()).'.'.$extension;
							
							if(move_uploaded_file($_FILES['file']['tmp_name'], $file_paths['links'].$filename)){
								if(editLinkFile($link_id, $filename, escapeData($_FILES['file']['name'])) > 0){
									$alerts->addAlert('The link was successfully edited.');
									$link_info['filename'] = $filename;
									$link_info['original_filename'] = $_FILES['file']['name'];
								} else {
									$alerts->addAlert('The link could not be edited.');
								}
							} else {
								$alerts->addAlert('The link could not be uploaded.');
							}
						} else {
							$alerts->addAlert('The link was successfully edited.');
						}
					}
				} else {
					$alerts->addAlert('The link could not be edited.');
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
<title>Admin: Announcements: Other Links | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='other_links'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="other_links.php">Other Links</a></li>
         	<li>Edit Link</li>
         </ul>
               
      	<h1>Admin: Announcements: Other Links: Edit Link</h1>
         
         <p>Complete the form below to edit a link.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>?lid=<?php echo $link_id; ?>" method="post" enctype="multipart/form-data">
         	<p><label for="title"><span class="required">*</span> Title</label> <input type="text" name="title" id="title" value="<?php echo htmlentities($link_info['title'], ENT_QUOTES); ?>" maxlength="255" /></p>
<?php
		if($isLink){
?>
            <p><label for="url"><span class="required">*</span> URL</label> <input type="text" name="url" id="url" value="<?php echo htmlentities($link_info['url'], ENT_QUOTES); ?>" maxlength="255" /></p>
<?php
		} else {
?>
				<p><label for="file">Replace File</label> <input type="file" name="file" id="file" /></p>
<?php
		}
?>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Edit Link" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>