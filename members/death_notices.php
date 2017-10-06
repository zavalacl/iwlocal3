<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/death_notices.php');
	
	// Selected Month
	$month = (!empty($_GET['m'])) ? (int) $_GET['m'] : getLatestDeathNoticeMonth();
	$year = (!empty($_GET['y'])) ? (int) $_GET['y'] : getLatestDeathNoticeYear();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Death Notices | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
      	<?php $page='death_notices'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>Death Notices</h1>
         
         <p>Placement of obituaries and death notices provides the opportunity for members of the union to express their condolences to the family members and attend the services honoring the deceased.</p>
         
			<div id="death_notices">
<?php
		$notices = getDeathNotices($year, $month);
		if($notices > 0){
			foreach($notices as $notice){
?>
				<div class="notice">
            	<span class="name"><?php echo $notice['first_name'].' '.$notice['last_name']; ?></span><br />
               <span class="date"><?php echo date('F j, Y', strtotime($notice['date'])); ?></span><br />
               
               <span class="info" style="display:inline-block; margin-top:5px;"><?php if($notice['age']){ ?><strong>Age:</strong> <?php echo $notice['age']; ?>&nbsp;&nbsp;<?php } ?>
               <?php if($notice['book_number']){ ?><strong>Book #:</strong> <?php echo $notice['book_number']; ?>&nbsp;&nbsp;<?php } ?>
               <?php if($notice['years_member']){ ?><?php echo $notice['years_member']; ?>-year member<?php } ?></span>
               
               <?php if($notice['visitation']){ ?><h3>Visitation</h3><?php echo $notice['visitation']; ?><?php } ?>
               <?php if($notice['funeral_service']){ ?><h3>Funeral Service</h3><?php echo $notice['funeral_service']; ?><?php } ?>
               <?php if($notice['burial']){ ?><h3>Burial</h3><?php echo $notice['burial']; ?><?php } ?>
            </div>
<?php	
			}
		} else {
?>
				<p>There are currently no death notices.</p>
<?php	
		}
?>
         </div><!-- div#death_notices -->
         
         <ul id="archive">
<?php
		$months = getDeathNoticeMonths();
		if($months > 0){
			
			$year = NULL;
			foreach($months as $month){
				if($month['year'] != $year){
?>
         	<li><span class="year"><?php echo $month['year']; ?></span>
            	<ul>
<?php
				}
?>
               	<li><a href="<?php echo getSelf(); ?>?y=<?php echo $month['year']; ?>&amp;m=<?php echo $month['month']; ?>"><?php echo $month_list[$month['month']]; ?></a></li>
<?php
				if($month['year'] != $year){
					$year = $month['year'];
?>
               </ul>
            </li>
<?php
				}
			}
		}
?>
			</ul>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>