<?php
	require('config.php');
	require('functions/officers.php');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Our Officers | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<style type="text/css">
div.main_left ul {
	list-style:none;
	margin:0;
	padding:0;
	width:100%;
	float:left;
}
div.main_left ul li {
	width:120px;
	float:left;
	display:block;
	margin:0 20px 10px 0;
	padding-bottom:10px;
	font-weight:bold;
	line-height:1.2em;
	font-size:.9em;
}
div.main_left ul li .title {
	font-weight:normal;
	color:#062644;
}
h2 {
	width:100%;
	border-bottom:1px solid;
	float:left;
	clear:left;
	margin:40px 0 15px 0;
}


</style>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='about_us'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='our_officers'; include("subnav_about_us.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Our Officers</h1>
         
         <div class="main_left">
         	<p>The officers of the Iron Workers Local Union No. 3 represent the membership, well - comprised of industry experts possessing great leadership skills and an abundance of industry knowledge.</p>

				<p>Pictured are the officers, identified by their specific role, responsibility, and geographic coverage area.</p>
       
<?php
		
		// Categories
		$categories = getOfficerCategories();
		if($categories > 0){
			foreach($categories as $category){
				
				// Officers
				$officers = getOfficers($category['category_id']);
				if($officers > 0){
?>  
         		<h2><?php echo $category['category']; ?></h2>
            <ul>
<?php
					foreach($officers as $officer){
?>
            	<li>
               	<img src="<?php echo new Image($officer['image'],120,149,'crop','officer_images'); ?>" alt="" /><br />
                  <?php echo $officer['first_name']. ' ' .$officer['last_name']; ?>
<?php
						if($officer['title']){
?>
									<br /><span class="title"><?php echo $officer['title']; ?></span>
<?php
						}
?>
               </li>
<?php
					}
?>
            </ul>
<?php
				}
			}
		}
?>
				
         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/officers.jpg" alt="" class="shadow" />
            
            <div class="box">
            	<span class="title">Project Gallery</span><br />
               Click here to view highlights of the exhibition of our work.<br />
               <a href="/our_work/" class="learn_more">View now</a>
            </div><!-- div.box -->
            <div class="box">
            	<span class="title">Events Calendar &amp; Registration</span><br />
               Click here to view the most current event information for Iron Workers Local Union No. 3.<br />
               <a href="/members/events.php" class="learn_more">Learn more now</a>
            </div><!-- div.box -->
            
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>