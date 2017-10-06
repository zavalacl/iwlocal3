<?php
	require('config.php');
	require('functions/content_areas.php');
	
	// Get Content Areas
	$content_zone_1 = getContentArea('apprentices_zone_1');
	$content_zone_2 = getContentArea('apprentices_zone_2');
	$content_zone_3 = getContentArea('apprentices_zone_3');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Apprenticeship Application Form | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />

<style type="text/css">
a.red, a .red {color: #6D0111}
</style>

<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='apprentices'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='application'; include("subnav_apprentices.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Apprenticeship Application Form</h1>
         
         <div class="main_left">
         	
            <h2>How Do I Become an Apprentice?</h2>
            
            <p>If you’re up to the challenge and are interested in becoming an iron worker and live in one of the following counties - Allegheny, Armstrong, Beaver, Blair, Butler, Cambria, Cameron, Center, Clarion, Clearfield, Clinton, Crawford, Elk, Erie, Fayette, Forest, Greene, Indiana, Jefferson, McKean, Potter, Washington, Warren or Westmoreland, then <a href="/files/Traditional-Apprenticeship-Application-SEPT2017.pdf" target="_blank" class="red"><strong>DOWNLOAD AN APPLICATION</strong></a> and follow the instructions for submission.</p>
            
            <p>Iron Workers Local Union No. 3 will continue to accept applications throughout the year.</p>
            
            <p>If you do not reside in one of the counties listed, please visit <a href="http://www.impact-net.org" target="_blank">www.impact-net.org</a> for the local union in your area.</p>
            
            <p style="font-weight:bold;">WE DO ACCEPT APPLICATIONS YEAR-ROUND. DON'T DELAY, GET YOUR APPLICATION IN EARLY TO RESERVE A SPOT FOR THE NEXT TEST.</p>
            
             
            
            
            <h2>CURRENT APPLICATION TIME LINE:</h2>
            
            <h3 style="margin-top:0;">ZONE 1</h3>
            
            <?php echo $content_zone_1 ['content']; ?>
            
            
						<h3>ZONE 2</h3>
            
            <?php echo $content_zone_2 ['content']; ?>
            
            
						<h3>ZONE 3</h3>
            
            <?php echo $content_zone_3 ['content']; ?>
            
         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/application_form.jpg" alt="" class="shadow" />
            
            <div class="box">
            	<span class="title">Project Gallery</span><br />
               Click here to view highlights of the exhibition of our work.<br />
               <a href="/our_work/" class="learn_more">View now</a>
            </div><!-- div.box -->
            <div class="box">
            	<span class="title">Tell a Friend</span><br />
               Click here to send this information and website to a friend.<br />
               <a href="tell_friend.php" class="learn_more">Send now</a>
            </div><!-- div.box -->
            
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>