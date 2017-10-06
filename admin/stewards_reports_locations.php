<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	if(isset($_GET['nl'])) $alerts->addAlert('The project location was successfully added.', 'success');
	
	
	// Delete a project location
	if(isset($_GET['d1'])){
		$location_id = (int) $_GET['d1'];
		if(deleteProjectLocation($location_id) > 0)
			$alerts->addAlert('The project location was successfully deleted.', 'success');
		else
			$alerts->addAlert('The project location could not be deleted.');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Stewards Reports: Project Locations | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_locations'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="stewards_reports_locations_add.php" class="icon_add">Add Location</a>
         
      	<h1>Admin: Stewards Reports: Project Locations</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
            	<th>Address</th>
            	<th>City</th>
            	<th>County</th>
               <th width="36">&nbsp;</th>
            </tr>
<?php
		$locations = getProjectLocations();
		if($locations > 0){
			foreach($locations as $location){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php echo $location['address']; ?></td>
            	<td><?php echo $location['city']; ?></td>
            	<td><?php echo $location['county']; ?></td>
               <td>
               	<a href="stewards_reports_locations_edit.php?lid=<?php echo $location['location_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $location['location_id']; ?>,'<?php echo getSelf(); ?>','project location',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="3">There are currently no project locations.</td>
            </tr>
<?php
		}
?>
         </table>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>