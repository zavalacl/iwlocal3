<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/events.php');
	
	$event_id = (int) $_GET['eid'];
	$event_info = getEvent($event_id);
	
	// Delete a Registration
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteEventRegistration($did) > 0)
			$alerts->addAlert('The event registration was successfully deleted.', 'success');
		else
			$alerts->addAlert('The event registration could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Event Registrations | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='events'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="events.php">Events</a></li>
         	<li>Event Registrations</li>
         </ul>
               
      	<h1>Admin: Event Registrations</h1>
         <p>
				<strong><?php echo $event_info['event']; ?></strong><br />
         	<span style="font-size:.9em;"><?php echo date('F j, Y', strtotime($event_info['date'])); ?></span>
         </p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
               <th>Name</th>
               <th>Email</th>
               <th>Phone</th>
               <th>Location</th>
               <th>Paid</th>
               <th width="35">&nbsp;</th>
            </tr>
<?php
		$registrations = getEventRegistrations($event_id);
		if($registrations > 0){
			foreach($registrations as $registration){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php echo $registration['last_name'].', '.$registration['first_name']; ?></td>
               <td><a href="mailto:<?php echo $registration['email']; ?>"><?php echo $registration['email']; ?></a></td>
               <td><?php echo formatPhone($registration['phone']); ?></td>
               <td><?php echo $registration['city'].', '.$registration['state']; ?></td>
               <td><?php echo ($registration['amount_paid']) ? '$'.number_format($registration['amount_paid'], 2) : 'N/A'; ?></td>
               <td>
               	<a href="events_registrations_view.php?rid=<?php echo $registration['registration_id']; ?>" class="icon_view">View</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $registration['registration_id']; ?>,'<?php echo getSelf(); ?>','event registration',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="6">There are currently no registrations for this event.</td>
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