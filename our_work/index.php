<?php
	require('config.php');
	require('functions/projects.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Our Work | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/our_work.css" />
<link rel="stylesheet" type="text/css" href="/js/fancybox/fancybox.css" media="screen" />
<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="/js/fancybox/fancybox.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$("#projects a.image").fancybox();
});
</script>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='our_work'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='project_gallery'; include("subnav_our_work.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Our Work</h1>
         
         <h2 style="margin-top:0;">Project Gallery</h2>
         
         <p>Now showing in a community near you are some of the greatest works created by an iron worker. Take a look at the results of expert craftsmanship, great pride, and industry passion, presented by Iron Workers Local Union No. 3.</p>

			<p>Highlights of the exhibition of our work include: sports arenas, corporate headquarters, entertainment venues, and hospitals, to name a few.</p>
         
         <div id="projects">
<?php
		$projects = getProjects();
		if($projects > 0){
			foreach($projects as $project){
				$image = getFirstProjectImage($project['project_id']);
				if($image > 0){
?>
				<div class="project">
            	<div class="img">
               	<a rel="group_<?php echo $project['project_id']; ?>" href="<?php echo new Image($image['filename'], 900, 700, 'fit', 'project_images'); ?>" class="image"<?php echo ($image['caption']) ? ' title="'.$image['caption'].'"' : ''; ?>><img src="<?php echo new Image($image['filename'], 200, 150, 'crop', 'project_images'); ?>" alt="" /></a>
                  <span class="link">Click on image to view gallery</span>
<?php
					$images = getProjectImages($project['project_id']);
					if($images > 0){
						array_shift($images);
						foreach($images as $image){
?>
						<a rel="group_<?php echo $project['project_id']; ?>" href="<?php echo new Image($image['filename'], 900, 700, 'fit', 'project_images'); ?>" class="image"<?php echo ($image['caption']) ? ' title="'.$image['caption'].'"' : ''; ?> style="display:none;"></a>
<?php	
						}
					}
?>
               </div>
               <div class="info">
               	<span class="title"><?php echo htmlentities($project['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                  <p><?php echo nl2br($project['description']); ?></p>
               </div>
            </div>
<?php
				}
			}
		}
?>
         </div>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>