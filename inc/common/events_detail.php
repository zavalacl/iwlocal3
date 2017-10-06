<div id="event">
<?php
			if($event_info['registration']){
?>
				<a href="events_register.php?eid=<?php echo $event_id; ?>" class="register">Register Now!</a>
<?php
			}
?> 
        
            <h1><?php echo $event_info['event']; ?></h1>
            <span class="date"><?php echo date('F j, Y', strtotime($event_info['date'])); ?></span>
<?php
         if($event_info['times']){
?>
            <br /><span class="info"><strong>Time:</strong> <?php echo $event_info['times']; ?></span>
<?php	
         }
   
         if($event_info['location']){
?>
            <br /><span class="info"><strong>Location:</strong> <?php echo $event_info['location']; ?></span>
<?php	
         }
?>
            <p><?php echo nl2br($event_info['description']); ?></p>
         </div><!-- div#event -->