<div id="events">
<?php
	// Selected Month
	$month = (!empty($_GET['m'])) ? (int) $_GET['m'] : date('n');
	$year = (!empty($_GET['y'])) ? (int) $_GET['y'] : date('Y');
	
	$prev_month = date('n', mktime(0,0,0,$month-1,1,$year));
	$prev_year = date('Y', mktime(0,0,0,$month-1,1,$year));
	$next_month = date('n', mktime(0,0,0,$month+1,1,$year));
	$next_year = date('Y', mktime(0,0,0,$month+1,1,$year));

	$first_of_month = mktime(0,0,0,$month,1,$year);
	$days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	$weeks_in_month = numMonthsWeeks($year, $month);
	
	$events = getMonthsEventsArray($year, $month);
?>
		<div id="calendar_control">
         <a href="<?php echo getSelf(); ?>?m=<?php echo $prev_month; ?>&amp;y=<?php echo $prev_year; ?>" class="previous"><img src="../img/arrow_prev.gif" alt="&lt;" /> Previous</a>
         <div class="month">
            <?php echo date('F', $first_of_month); ?> <?php echo date('Y', $first_of_month); ?>
         </div>
         <a href="<?php echo getSelf(); ?>?m=<?php echo $next_month; ?>&amp;y=<?php echo $next_year; ?>" class="next">Next <img src="../img/arrow_next.gif" alt="&gt;" /></a>
      </div><!-- div#calendar_control -->
      
      <div id="calendar">
         <table>
            <tr>
               <th>Sunday</th>
               <th>Monday</th>
               <th>Tuesday</th>
               <th>Wednesday</th>
               <th>Thursday</th>
               <th>Friday</th>
               <th>Saturday</th>
            </tr>
<?php
   $day = 0;
   for($w=1; $w<=$weeks_in_month; $w++){
?>
          <tr>
<?php
      for($d=0; $d<7; $d++){
         if( ($w==1 && $d>=date('w', $first_of_month)) || ($day > 0 && $day < $days_in_month) ){
            $day++;
?>
            <td<?php echo ($day==date('j') && $month==date('n') && $year==date('Y')) ? ' class="today"' : '' ?>>
               <span class="day"><?php echo $day; ?></span>
<?php
				if($events[$day]){
?>
					<ul>
<?php
					foreach($events[$day] as $event){
?>
               	<li><a href="events_detail.php?eid=<?php echo $event['event_id']; ?>"><?php echo $event['event']; ?></a></li>
<?php
				
					}
?>
					</ul>
<?php
				}
?>
            </td>
<?php
         } else {
?>
            <td class="pad">&nbsp;</td>
<?php
         }
      }
?>
          </tr>
<?php
   }
?>
         </table>
      </div><!-- div#calendar -->
<?php
	
	/*
		$events = getUpcomingEvents();
		if($events > 0){
			foreach($events as $event){
?>
				<div class="event">
<?php
				if($event['registration']){
?>
					<a href="events_register.php?eid=<?php echo $event['event_id']; ?>" class="register">Register Now!</a>
<?php
				}
?>
            
            	<a href="events_detail.php?eid=<?php echo $event['event_id']; ?>" class="event"><?php echo $event['event']; ?></a>
               <span class="date"><?php echo date('F j, Y', strtotime($event['date'])); ?></span>
<?php
				if($event['times']){
?>
					<br /><strong>Time:</strong> <?php echo $event['times']; ?>
<?php	
				}

				if($event['location']){
?>
					<br /><strong>Location:</strong> <?php echo $event['location']; ?>
<?php	
				}
?>
            </div>
<?php	
			}
		} else {
?>
				<p>There are no upcoming events at this time.</p>
<?php	
		}*/
?>
</div><!-- div#events -->
