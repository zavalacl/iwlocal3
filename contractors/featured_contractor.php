<?php
	require('config.php');
	require('functions/contractors.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Contractor of the Month | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/contractors.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='contractors'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='featured_contractor'; include("subnav_contractors.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Contractor of the Month</h1>
         
<?php
         $contractor = getCurrentFeaturedContractor();
         if($contractor > 0){
?>
         <div id="featured">
<?php
            if($contractor['image_filename']){
?>
            <div class="img">
               <img src="<?php echo new Image($contractor['image_filename'], 200, 300, 'fit', 'contractor_images'); ?>" alt="" class="shadow" />
            </div>
<?php
            }
?>
            <div class="info">
               <span class="contractor"><?php echo $contractor['contractor']; ?></span>
               
               <p><?php echo nl2br($contractor['description']); ?></p>
            </div>
         </div>
<?php
         } else {
?>
         <p>There is currently no featured contractors.</p>
<?php
         }
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>