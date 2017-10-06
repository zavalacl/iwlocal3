<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/content_areas.php');
	
	$content = getContentArea('training');
	$content_alerts = getContentArea('training_alerts');
	$content_welding = getContentArea('training_welding');
	$content_journeyman = getContentArea('training_journeyman');
	$content_site_specific = getContentArea('training_site_specific');
	$content_apprenticeship = getContentArea('training_apprenticeship');
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Training | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<style type="text/css">
h3 {
	border-bottom:1px solid;
}
div.alerts {
	width:730px;
	padding:20px;
	border:2px solid #900;
	margin:10px 0 30px 0;
	color:#900;
}
</style>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='training'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>Training</h1>
         
         <?php echo $content['content']; ?>
         
         <?php echo ($content_alerts['content']) ? '<div class="alerts">'.$content_alerts['content'].'</div>' : ''; ?>
         
         
         <h2>Enroll Today!</h2>
         
         <p>Training and development is an ongoing process with Iron Workers Local Union No. 3. Review the specific course listings to determine the schedule of the specific course or seminar of interest. We encourage that you join us today!</p>
         
         
         <h2>Courses Offered</h2>
         
         <h3 style="margin-top:0;">Welding Certification</h3>
         <?php echo $content_welding['content']; ?>
         
         
         <h3>Journeyman Upgrading / Retraining Courses</h3>
         <?php echo $content_journeyman['content']; ?>
         
         
         <h3>Site Specific Training</h3>
         <?php echo $content_site_specific['content']; ?>
         
         
         <h3>Apprenticeship Training</h3>
         <?php echo $content_apprenticeship['content']; ?>
        
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>