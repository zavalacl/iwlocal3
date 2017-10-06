<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/projects.php');
	
	$image_id = (int) $_GET['iid'];
	
	
	// Edit Project Image Caption
	if(isset($_POST['submit'])){
		try {
			
			$validator = new Validator();
			$validator->checkTextLength('caption', 0, 128);
			$validator->noFilter('caption');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editProjectImageCaption($image_id, $clean['caption'])){
					$alerts->addAlert('The image caption was successfully edited.', 'success');
				} else {
					$alerts->addAlert('The image caption could not be edited.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	// Get Image Info
	$image_info = getProjectImage($image_id);
	
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
         	<li><a href="projects_edit.php?pid=<?php echo $image_info['project_id']; ?>">Edit Project</a></li>
            <li>Edit Project Image Caption</li>
         </ul>
               
      	<h1>Admin: Edit Project Image Caption</h1>
         
         <div class="admin_left">
            <p>Complete the form below to edit the image's caption.</p>
            
            <?php if($alerts->hasAlerts()) echo $alerts; ?>
            
            <form action="<?php echo getSelf(); ?>?iid=<?php echo $image_id; ?>" method="post" enctype="multipart/form-data">
               <p><label for="caption">Caption</label> <input name="caption" id="caption" value="<?php echo $image_info['caption']; ?>" maxlength="128" /></p>
               <p class="submit"><input type="submit" name="submit" id="submit" value="Edit Caption" /></p>
            </form> 
         </div><!-- div.admin_left -->
         
         <div class="admin_right" style="padding-top:15px;">
         	<img src="<?php echo new Image($image_info['filename'],250,250,'fit','project_images'); ?>" alt="" class="shadow" />
         </div><!-- div.admin_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>