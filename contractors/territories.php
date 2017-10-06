<?php
	require('config.php');
	require('functions/contractors.php');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Territories | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<style type="text/css">
#map {
	margin-bottom:15px;
}
div.territory {
	width:774px;
	padding:15px 0;
	border-bottom:1px solid #ddd;
}
div.territory div.half {
	width:260px;
}
div.territory .title {
	font-size:.9em;
	color:#062644;
}
</style>
<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript">
// Embed map
swfobject.embedSWF("../swf/pennsylvania_territories.swf", "mapFlash", "774", "530", "9.0.0");

// Scroll down to selected id (received via Flash)
function jumpToTarget(id){
	$('html,body').animate({scrollTop: $("#"+id).offset().top},'slow');
}
</script>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='contractors'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='territories'; include("subnav_contractors.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Territories</h1>
         
         <p><a href="login.php">Login</a> to download a wage rate sheet for your territory.</p>
         
         <div id="map">
            <div id="mapFlash">
               <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
            </div>
         </div>
         
<?php
		
		// Territories
		$territories = getContractorRepTerritories();
		if($territories > 0){
			foreach($territories as $territory){
			
				// Reps
				$reps = getContractorReps($territory['territory_id']);
				if($reps > 0){
?>
         	<div class="territory"<?php echo ($territory['key']) ? ' id="'.$territory['key'].'"' : ''; ?>>
<?php
					foreach($reps as $rep){
?>
         		<div class="half">
               <strong><?php echo $rep['first_name'].' '.$rep['last_name']; ?></strong>
<?php
						// Title
						if($rep['title']){
?>
               <br /><span class="title"><?php echo $rep['title']; ?></span>
<?php
						}
						
						// Office phone
						if($rep['office']){
?>
               <br />Office: <?php echo formatPhone($rep['office']); ?>
<?php
						}
						
						// Fax
						if($rep['fax']){
?>
               <br />Fax: <?php echo formatPhone($rep['fax']); ?>
<?php
						}
						
						// Cell
						if($rep['cell']){
?>
               <br />Cell: <?php echo formatPhone($rep['cell']); ?>
<?php
						}
						
						// Email
						if($rep['email']){
?>
               <br />Email: <a href="mailto:<?php echo $rep['email']; ?>"><?php echo $rep['email']; ?></a>
<?php
						}
						
						// Note
						if($rep['note']){
?>
								<br /><em class="note"><?php echo $rep['note']; ?></em>
<?php
						}
?>
            </div>
<?php
					}
?>
         </div>
<?php
				}
			}
		}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>