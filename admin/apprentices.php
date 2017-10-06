<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/content_areas.php');
	
	
	// Edit Content Area
	if(isset($_POST['submit'])){
		try {
			$required = array('key','text');
			
			$validator = new Validator($required);
			$validator->noFilter('key');
			$validator->noFilter('text');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editContentArea($filtered['key'], $clean['text']) > 0){
					$alerts->addAlert('The text was successfully edited.', 'success');
				} else {
					$alerts->addAlert('The text could not be edited.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Get Content Areas
	$content_alerts = getContentArea('apprentices_alerts');
	$content_application = getContentArea('apprentices_application');
	$content_zone_1 = getContentArea('apprentices_zone_1');
	$content_zone_2 = getContentArea('apprentices_zone_2');
	$content_zone_3 = getContentArea('apprentices_zone_3');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Training | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='apprentices'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li>Apprentices</li>
         </ul>
               
      	<h1>Admin: Apprentices</h1>
         
         <p>Complete the forms below to edit the Apprentices content.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <h2 style="margin:40px 0 -10px 0; float:left; width: 100%;">Alerts</h2>
         <form action="<?php echo getSelf(); ?>" method="post">
         	<input type="hidden" name="key" value="apprentices_alerts" />
            <p><div class="label">Text</div><textarea name="text" class="wysiwyg"><?php echo $content_alerts['content']; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" value="Edit Content" /></p>
         </form> 
         
         
         <h3 style="margin:40px 0 -10px 0; float:left; width: 100%;">High school diploma or GED</h3>
				 <form action="<?php echo getSelf(); ?>" method="post">
         	<input type="hidden" name="key" value="apprentices_application" />
            <p><div class="label">Text</div><textarea name="text" class="wysiwyg"><?php echo $content_application['content']; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" value="Edit Content" /></p>
         </form> 
         
         
         <h3 style="margin:40px 0 -10px 0; float:left; width: 100%;">Zone 1</h3>
				 <form action="<?php echo getSelf(); ?>" method="post">
         	<input type="hidden" name="key" value="apprentices_zone_1" />
            <p><div class="label">Text</div><textarea name="text" class="wysiwyg"><?php echo $content_zone_1['content']; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" value="Edit Content" /></p>
         </form>
         
         <h3 style="margin:40px 0 -10px 0; float:left; width: 100%;">Zone 2</h3>
				 <form action="<?php echo getSelf(); ?>" method="post">
         	<input type="hidden" name="key" value="apprentices_zone_2" />
            <p><div class="label">Text</div><textarea name="text" class="wysiwyg"><?php echo $content_zone_2['content']; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" value="Edit Content" /></p>
         </form>
         
         <h3 style="margin:40px 0 -10px 0; float:left; width: 100%;">Zone 3</h3>
				 <form action="<?php echo getSelf(); ?>" method="post">
         	<input type="hidden" name="key" value="apprentices_zone_3" />
            <p><div class="label">Text</div><textarea name="text" class="wysiwyg"><?php echo $content_zone_3['content']; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" value="Edit Content" /></p>
         </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>