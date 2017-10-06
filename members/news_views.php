<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/news_views.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>News and Views | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
      	<?php $page='news_views'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>News and Views</h1>
         
         <p>Review the most current and past industry issues within Iron Workers Local Union No. 3’s newsletters.</p>
         
         <div id="news">
<?php
		$posts = getAllNews();
		if($posts > 0){
			foreach($posts as $post){
?>
				<div class="post">
               <span class="date">Posted <?php echo date('F j, Y', strtotime($post['date'])); ?></span>
               <p><?php echo $post['news']; ?></p>
            </div>
<?php	
			}
		} else {
?>
				<p>There are currently no News &amp; Views posts.</p>
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