<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/news.php');
	
	if(isset($_GET['nn'])) $alerts->addAlert('The news was successfully added.', 'success');
	
	
	// Delete a News Item
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteNews($did) > 0)
			$alerts->addAlert('The news item was successfully deleted.', 'success');
		else
			$alerts->addAlert('The news item could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Latest News | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='news'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="news_add.php" class="icon_add">Add News</a>
         
      	<h1>Admin: Latest News</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <div id="news">
<?php
		$items = getAllNews();
		if($items > 0){
			foreach($items as $item){
?>
            <div class="item">
            	<div class="news">               
                  <span class="date"><?php echo date('F j, Y', strtotime($item['date'])); ?></span>
                  <p><?php echo substr(strip_tags($item['news']), 0, 300).'...'; ?></p>
               </div>
               <ul class="options">
               	<li><a href="news_edit.php?nid=<?php echo $item['news_id']; ?>">Edit</a></li>
                  <li><a href="javascript:confirm_deletion(1,<?php echo $item['news_id']; ?>,'<?php echo getSelf(); ?>','news item',0);">Delete</a></li>
               </ul>
            </div>
<?php
			}
		} else {
?>
				<p>There are currently no news entries.</p>
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