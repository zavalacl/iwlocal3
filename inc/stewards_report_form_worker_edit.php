<?php
	require_once('constants.php');
?>
<div class="worker" style="background-color:<?php echo $bkgd; ?>;">
	<div class="num"><?php echo $i; ?></div>
	<div class="book_number"><?php echo $book_number; ?></div>
   <div class="first_name"><input type="text" name="eworker_first_name_<?php echo $worker['worker_id']; ?>" id="worker_first_name_e<?php echo $worker['worker_id']; ?>" value="<?php echo htmlentities($worker['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="last_name"><input type="text" name="eworker_last_name_<?php echo $worker['worker_id']; ?>" id="worker_last_name_e<?php echo $worker['worker_id']; ?>" value="<?php echo htmlentities($worker['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="local_number"><input type="text" name="eworker_local_number_<?php echo $worker['worker_id']; ?>" id="worker_local_number_e<?php echo $worker['worker_id']; ?>" class="numeric" value="<?php echo htmlentities($worker['local_number'], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="type">
   		<select name="eworker_type_<?php echo $worker['worker_id']; ?>" id="worker_type_<?php echo $worker['worker_id']; ?>">
   			<option value=""></option>
   			<option value="<?php echo WORKER_TYPE_JOURNEYMAN; ?>"<?php echo ($worker['type']==WORKER_TYPE_JOURNEYMAN) ? ' selected="selected"' : ''; ?>>Journeyman</option>
   			<option value="<?php echo WORKER_TYPE_APPRENTICE; ?>"<?php echo ($worker['type']==WORKER_TYPE_APPRENTICE) ? ' selected="selected"' : ''; ?>>Apprentice</option>
   			<option value="<?php echo WORKER_TYPE_PROBATIONARY; ?>"<?php echo ($worker['type']==WORKER_TYPE_PROBATIONARY) ? ' selected="selected"' : ''; ?>>Probationary</option>
   		</select>
   </div>
   <div class="month_dues_paid">
      <select name="eworker_month_dues_paid_month_<?php echo $worker['worker_id']; ?>" id="worker_month_dues_paid_month_e<?php echo $worker['worker_id']; ?>" style="margin-bottom:2px;">
         <option value="">[Month]</option>
<?php
		for($j=1; $j<=12; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($month_dues_paid==$j) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$j]; ?></option>
<?php	
		}
?>
      </select> 
      <input name="eworker_month_dues_paid_year_<?php echo $worker['worker_id']; ?>" id="worker_month_dues_paid_year_e<?php echo $worker['worker_id']; ?>" value="<?php echo $year_dues_paid; ?>" style="width: 50px;" maxlength="4" />
   </div>
   <div class="hours_paid">
   	<div class="time_straight">
   		<label for="worker_time_straight_<?php echo $worker['worker_id']; ?>">Straight Time</label>
   		<input type="text" name="eworker_time_straight_<?php echo $worker['worker_id']; ?>" id="worker_time_straight_e<?php echo $worker['worker_id']; ?>" class="numeric" value="<?php echo htmlentities($worker['time_straight'], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked('e'+<?php echo $worker['worker_id']; ?>);" />
   	</div>
   	<div class="time_half">
   		<label for="worker_time_half_<?php echo $worker['worker_id']; ?>">Time & 1/2</label>
   		<input type="text" name="eworker_time_half_<?php echo $worker['worker_id']; ?>" id="worker_time_half_e<?php echo $worker['worker_id']; ?>" class="numeric" value="<?php echo htmlentities($worker['time_half'], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked('e'+<?php echo $worker['worker_id']; ?>);" />
   	</div>
   	<div class="time_double">
   		<label for="worker_time_double_<?php echo $worker['worker_id']; ?>">Double Time</label>
   		<input type="text" name="eworker_time_double_<?php echo $worker['worker_id']; ?>" id="worker_time_double_e<?php echo $worker['worker_id']; ?>" class="numeric" value="<?php echo htmlentities($worker['time_double'], ENT_QUOTES, 'utf-8'); ?>" onkeyup="updateTotalWorkerHoursWorked('e'+<?php echo $worker['worker_id']; ?>);" />
   	</div>
   	<div class="total_hours_worked">
   		<label for="worker_hours_worked_<?php echo $worker['worker_id']; ?>">Hours Worked</label>
   		<input type="text" name="eworker_hours_worked_<?php echo $worker['worker_id']; ?>" id="worker_hours_worked_e<?php echo $worker['worker_id']; ?>" readonly="readonly" value="<?php echo htmlentities($worker['hours_worked'], ENT_QUOTES, 'utf-8'); ?>" />
   	</div>
   	<div class="total_hours_paid">
   		<label for="worker_hours_paid_<?php echo $worker['worker_id']; ?>">Hours Paid</label>
   		<input type="text" name="eworker_hours_paid_<?php echo $worker['worker_id']; ?>" id="worker_hours_paid_e<?php echo $worker['worker_id']; ?>" readonly="readonly" value="<?php echo htmlentities($worker['hours_paid'], ENT_QUOTES, 'utf-8'); ?>" />
   	</div>
   	<div class="delete_worker">
   		<a href="javascript:confirm_deletion(5, <?php echo $worker['worker_id']; ?>, '<?php echo getSelf(); ?>?rid=<?php echo $report_id; ?>', 'worker', 1);">Delete</a>
   	</div>
   </div> <!-- .hours_paid -->
</div><!-- div.worker -->
