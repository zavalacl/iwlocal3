<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/death_notices.php');
	
	if(isset($_GET['nn'])) $alerts->addAlert('The notice was successfully added.', 'success');
	
	
	// Selected Month
	$month = (!empty($_GET['m'])) ? (int) $_GET['m'] : getLatestDeathNoticeMonth();
	$year = (!empty($_GET['y'])) ? (int) $_GET['y'] : getLatestDeathNoticeYear();
	
	
	// Delete a Death Notice
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteDeathNotice($did) > 0)
			$alerts->addAlert('The notice was successfully deleted.', 'success');
		else
			$alerts->addAlert('The notice could not be deleted.');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Death Notices | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='death_notices'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="death_notices_add.php" class="icon_add">Add Death Notice</a>
         
      	<h1>Admin: Death Notice</h1>
         
         <div id="notices">
         
				<?php if($alerts->hasAlerts()) echo $alerts; ?>
            
            <table>
               <tr>
                  <th>Name</th>
                  <th>Date</th>
                  <th>&nbsp;</th>
               </tr>
<?php
		$notices = getDeathNotices($year, $month);
		if($notices > 0){
			foreach($notices as $notice){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
               <tr style="background-color:<?php echo $bkgd; ?>;">
                  <td><?php echo $notice['first_name'].' '.$notice['last_name']; ?></td>               
                  <td><?php echo date('F j, Y', strtotime($notice['date'])); ?></td>
                  <td>
                     <a href="death_notices_edit.php?nid=<?php echo $notice['notice_id']; ?>" class="icon_edit">Edit</a>
                     <a href="javascript:confirm_deletion(1,<?php echo $notice['notice_id']; ?>,'<?php echo getSelf(); ?>','death notice',0);" class="icon_delete">Delete</a>
                  </td>
               </tr>
<?php
			}
		} else {
?>
               <tr>
                  <td colspan="3">There are currently no notices.</td>
               </tr>
<?php
		}
?>
         	</table>
         </div><!-- div#notices -->
         
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