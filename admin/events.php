<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/events.php');
	
	if(isset($_GET['ne'])) $alerts->addAlert('The event was successfully added.', 'success');
	
	
	// Delete an Event
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteEvent($did) > 0)
			$alerts->addAlert('The event was successfully deleted.', 'success');
		else
			$alerts->addAlert('The event could not be deleted.');
	}
	
	
	// Get Events
	if(isset($_GET['past'])){
		$events = getPastEvents();
		$past = true;
	} else {
		$events = getUpcomingEvents();	
		$past = false;
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Events | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
      
      	<a href="events_add.php" class="icon_add">Add Event</a>
         
      	<h1>Admin: <?php echo ($past) ? 'Past' : 'Upcoming'; ?> Events</h1>
         
         <div style="width:100%;padding-bottom:15px;">
         	<a href="events.php">Upcoming Events</a> | <a href="events.php?past">Past Events</a>
         </div><br />
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
               <th>Event</th>
               <th>Date</tdh>
               <th>Allow Registration</th>
               <th width="45">&nbsp;</th>
            </tr>
<?php
		if($events > 0){
			foreach($events as $event){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php echo $event['event']; ?></td>
               <td><?php echo date('n/j/Y', strtotime($event['date'])); ?></td>
               <td>
						<?php echo ($event['registration']) ? 'Yes' : 'No'; ?>
<?php
				if(getEventRegistrations($event['event_id']) > 0){
?>
						<a href="events_registrations.php?eid=<?php echo $event['event_id']; ?>" style="font-size:.9em;">(View)</a>
<?php
				}
?>
               </td>
               <td width="45">
               	<a href="events_edit.php?eid=<?php echo $event['event_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $event['event_id']; ?>,'<?php echo getSelf(); ?>','event and all registered parties',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="4">There are currently no upcoming events.</td>
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