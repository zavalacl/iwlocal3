<?php
	require_once('constants.php');
	$bkgd = ($bkgd) ? $bkgd : $_POST['bkgd'];
	$i = ($i) ? $i : $_POST['i'];
	$total_hours += $_POST['worker_hours_paid_'.$i];
?>
<div class="worker" style="background-color:<?php echo $bkgd; ?>;">
	<div class="num"><?php echo $i; ?></div>
	<div class="book_number"><input type="text" name="worker_book_number_<?php echo $i; ?>" id="worker_book_number_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_book_number_'.$i], ENT_QUOTES, 'utf-8'); ?>" onkeyup="findUser(this.value, <?php echo $i; ?>)" /></div>
   <div class="first_name"><input type="text" name="worker_first_name_<?php echo $i; ?>" id="worker_first_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['worker_first_name_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="last_name"><input type="text" name="worker_last_name_<?php echo $i; ?>" id="worker_last_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['worker_last_name_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="local_number"><input type="text" name="worker_local_number_<?php echo $i; ?>" id="worker_local_number_<?php echo $i; ?>" class="numeric" value="<?php echo htmlentities($_POST['worker_local_number_'.$i], ENT_QUOTES, 'utf-8'); ?>" /></div>
   <div class="type">
      <input type="radio" name="worker_type_<?php echo $i; ?>" id="worker_type_journeyman_<?php echo $i; ?>" value="<?php echo WORKER_TYPE_JOURNEYMAN; ?>"<?php echo (empty($_POST['worker_type_'.$i]) || $_POST['worker_type_'.$i]==WORKER_TYPE_JOURNEYMAN) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="worker_type_journeyman_<?php echo $i; ?>" class="plain">Journeyman</label>
      <input type="radio" name="worker_type_<?php echo $i; ?>" id="worker_type_apprentice_<?php echo $i; ?>" value="<?php echo WORKER_TYPE_APPRENTICE; ?>"<?php echo ($_POST['worker_type_'.$i]==WORKER_TYPE_APPRENTICE) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="worker_type_apprentice_<?php echo $i; ?>" class="plain">Apprentice</label> 
      <input type="radio" name="worker_type_<?php echo $i; ?>" id="worker_type_probationary_<?php echo $i; ?>" value="<?php echo WORKER_TYPE_PROBATIONARY; ?>"<?php echo ($_POST['worker_type_'.$i]==WORKER_TYPE_PROBATIONARY) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="worker_type_probationary_<?php echo $i; ?>" class="plain">Probationary</label>
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
   <div class="hours_paid"><input type="text" name="worker_hours_paid_<?php echo $i; ?>" id="worker_hours_paid_<?php echo $i; ?>" class="numeric" value="<?php echo $_POST['worker_hours_paid_'.$i]; ?>" onkeyup="updateTotalWorkerHours();" /></div>
</div><!-- div.worker -->