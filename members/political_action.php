<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/political_action.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Political Action | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='political_action'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>Political Action</h1>
         
         <p>A collection of political resources is available for your review. These materials are continually updated and feature the most current information affecting our industry. Please take the time to read through the materials provided before forming your political viewpoint.</p>
         <p>We also ask that you continue to stay active in the voting process in the future as you have in the past.  It is imperative to do so to preserve this union and the livelihoods it provides.</p>
         
         <div id="political_action_files">
           <?php

		$files = getPoliticalActionFiles();
		if($files > 0){
			foreach($files as $file){
				$link = ($file['url']) ? forceURI($file['url']) : '../download.php?t=political_action_files&amp;id='.$file['file_id'];
?>
           <div class="file">
<?php
				if($file['image']){
?>
						<a href="<?php echo $link; ?>" target="_blank" class="img"><img src="<?php echo new Image($file['image'], 150, 150, 'fit', 'political_action_images'); ?>" alt=""></a>
<?php
				} else {
?>
						<div class="img"></div>
<?php
				}
?>
						<a href="<?php echo $link; ?>" target="_blank" class="title"><?php echo $file['title']; ?></a>
<?php
				if($file['comment']){
?>
						<p class="comment"><?php echo $file['comment']; ?></p>
<?php
				}
?>
					</div> <!-- .file -->
<?php	
			}
		} else {
?>
					<p>There are currently no files.</p>
<?php	
		}
?>
     </div><!-- div#news -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>