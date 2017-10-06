<?php
	require_once('constants.php');
	$i = ($i) ? $i : $_POST['i'];
	global $month_list;
?>
<div class="worker">
	<div class="num"><?php echo $i; ?></div>
	<div class="book_number"><input type="text" name="worker_book_number_<?php echo $i; ?>" id="worker_book_number_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_book_number_'.$i], ENT_QUOTES, 'utf-8'); ?>" onkeyup="findUser(this.value, <?php echo $i; ?>)" /></div>
   <div class="first_name"><input type="text" name="worker_first_name_<?php echo $i; ?>" id="worker_first_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['worker_first_name_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="last_name"><input type="text" name="worker_last_name_<?php echo $i; ?>" id="worker_last_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['worker_last_name_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="local_number"><input type="text" name="worker_local_number_<?php echo $i; ?>" id="worker_local_number_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_local_number_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="type">
   		<select name="worker_type_<?php echo $i; ?>" id="worker_type_<?php echo $i; ?>">
   			<option value=""></option>
   			<option value="<?php echo WORKER_TYPE_JOURNEYMAN; ?>"<?php echo ($_POST['worker_type_'.$i]==WORKER_TYPE_JOURNEYMAN) ? ' selected="selected"' : ''; ?>>Journeyman</option>
   			<option value="<?php echo WORKER_TYPE_APPRENTICE; ?>"<?php echo ($_POST['worker_type_'.$i]==WORKER_TYPE_APPRENTICE) ? ' selected="selected"' : ''; ?>>Apprentice</option>
   			<option value="<?php echo WORKER_TYPE_PROBATIONARY; ?>"<?php echo ($_POST['worker_type_'.$i]==WORKER_TYPE_PROBATIONARY) ? ' selected="selected"' : ''; ?>>Probationary</option>
   		</select>
   </div>
   <div class="month_dues_paid">
      <select name="worker_month_dues_paid_month_<?php echo $i; ?>" id="worker_month_dues_paid_month_<?php echo $i; ?>" style="margin-bottom:2px;">
         <option value="">[Month]</option>
<?php
		for($j=1; $j<=12; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($_POST['worker_month_dues_paid_month_'.$i]==$j) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$j]; ?></option>
<?php	
		}
?>
      </select> 
      <select name="worker_month_dues_paid_year_<?php echo $i; ?>" id="worker_month_dues_paid_year_<?php echo $i; ?>">
         <option value="">[Year]</option>
<?php
		for($y=date('Y')-1; $y<=date('Y')+3; $y++){
?>
         <option value="<?php echo $y; ?>"<?php echo ($_POST['worker_month_dues_paid_year_'.$i]==$y) ? ' selected="selected"' : ''; ?>><?php echo $y; ?></option>
<?php	
		}
?>
      </select>
      <?php /*<input type="text" name="worker_month_dues_paid_year_<?php echo $i; ?>" id="worker_month_dues_paid_year_<?php echo $i; ?>" class="numeric" value="<?php echo ($_POST['worker_month_dues_paid_year_'.$i]) ? htmlentities($_POST['worker_month_dues_paid_year_'.$i], ENT_QUOTES, 'utf-8') : 'Year'; ?>" maxlength="4" style="width:40px;" />*/ ?>
   </div>
   <div class="hours_paid">
   	<div class="time_straight">
   		<label for="worker_time_straight_<?php echo $i; ?>">Straight Time</label>
   		<input type="text" name="worker_time_straight_<?php echo $i; ?>" id="worker_time_straight_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_time_straight_'.$i], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked(<?php echo $i; ?>);" />
   	</div>
   	<div class="time_half">
   		<label for="worker_time_half_<?php echo $i; ?>">Time & 1/2</label>
   		<input type="text" name="worker_time_half_<?php echo $i; ?>" id="worker_time_half_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_time_half_'.$i], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked(<?php echo $i; ?>);" />
   	</div>
   	<div class="time_double">
   		<label for="worker_time_double_<?php echo $i; ?>">Double Time</label>
   		<input type="text" name="worker_time_double_<?php echo $i; ?>" id="worker_time_double_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_time_double_'.$i], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked(<?php echo $i; ?>);" />
   	</div>
   	<div class="total_hours_worked">
   		<label for="worker_hours_worked_<?php echo $i; ?>">Hours Worked</label>
   		<input type="text" name="worker_hours_worked_<?php echo $i; ?>" id="worker_hours_worked_<?php echo $i; ?>" readonly="readonly" value="<?php echo htmlentities($_POST['worker_hours_worked_'.$i], ENT_QUOTES, 'utf-8'); ?>" />
   	</div>
   	<div class="total_hours_paid">
   		<label for="worker_hours_paid_<?php echo $i; ?>">Hours Paid</label>
   		<input type="text" name="worker_hours_paid_<?php echo $i; ?>" id="worker_hours_paid_<?php echo $i; ?>" readonly="readonly" value="<?php echo htmlentities($_POST['worker_hours_paid_'.$i], ENT_QUOTES, 'utf-8'); ?>" />
   	</div>
   </div> <!-- .hours_paid -->
</div><!-- div.worker -->
