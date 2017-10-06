<div id="event">
        
            <h1>Register for &ldquo;<?php echo $event_info['event']; ?>&rdquo;</h1>
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
				<?php if($alerts->hasAlerts()) echo $alerts; ?>
<?php
			if(!$success){
?>
				<p style="margin:15px 0;">Fill out the form below to register for this event.</p>
            
            <form action="<?php getSelf(); ?>?eid=<?php echo $event_id; ?>" method="post">
            	<p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo (!empty($_POST['first_name'])) ? stripslashes($_POST['first_name']) : ''; ?>" /></p>
               <p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo (!empty($_POST['last_name'])) ? stripslashes($_POST['last_name']) : ''; ?>" /></p>
               <p><label for="email"><span class="required">*</span> Email</label> <input type="text" name="email" id="email" value="<?php echo (!empty($_POST['email'])) ? stripslashes($_POST['email']) : ''; ?>" /></p>
               <p><label for="address"><span class="required">*</span> Address</label> <input type="text" name="address" id="address" value="<?php echo (!empty($_POST['address'])) ? stripslashes($_POST['address']) : ''; ?>" /></p>
               <p><label for="address_2">Address 2</label> <input type="text" name="address_2" id="address_2" value="<?php echo (!empty($_POST['address_2'])) ? stripslashes($_POST['address_2']) : ''; ?>" /></p>
            	<p><label for="city"><span class="required">*</span> City</label> <input type="text" name="city" id="city" value="<?php echo (!empty($_POST['city'])) ? stripslashes($_POST['city']) : ''; ?>" /></p>
               <p><label for="state"><span class="required">*</span> State</label> 
               	<select name="state" id="state">
                  	<option value=""></option>
<?php
					foreach($state_list as $abbrev=>$state){
?>
							<option value="<?php echo $abbrev; ?>"<?php echo (!empty($_POST['state']) && $_POST['state']==$abbrev) ? ' selected="selected"' : ''; ?>><?php echo $state; ?></option>
<?php	
					}
?>
                  </select>
               </p>
               <p><label for="zip"><span class="required">*</span> Zip</label> <input type="text" name="zip" id="zip" style="width:80px;" value="<?php echo (!empty($_POST['zip'])) ? stripslashes($_POST['zip']) : ''; ?>" /></p>
               <p><label for="phone"><span class="required">*</span> Phone</label> <input type="text" name="phone" id="phone" value="<?php echo (!empty($_POST['phone'])) ? stripslashes($_POST['phone']) : ''; ?>" /></p>
               <p><label for="comments">Comments</label> <textarea name="comments" id="comments" cols="40" rows="5"><?php echo (!empty($_POST['comments'])) ? stripslashes($_POST['comments']) : ''; ?></textarea></p>
               <p class="submit"><input type="submit" name="submit" id="submit" value="Submit" /></p>
            </form>
<?php
			}
?>
            
         </div><!-- div#event -->