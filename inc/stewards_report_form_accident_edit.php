<?php
	require_once('constants.php');
	$bkgd = ($bkgd) ? $bkgd : $_POST['bkgd'];
	
	list($year, $month, $day) = explode('-', $accident['date']);
?>
<div class="accident" style="background-color:<?php echo $bkgd; ?>;">
   <div class="description"><textarea name="eaccident_description_<?php echo $accident['accident_id']; ?>" id="eaccident_description_<?php echo $accident['accident_id']; ?>" cols="50" rows="2"><?php echo htmlentities($accident['description'], ENT_QUOTES, 'utf-8'); ?></textarea></div>
   <div class="date">
      <select name="eaccident_month_<?php echo $accident['accident_id']; ?>" id="eaccident_month_<?php echo $accident['accident_id']; ?>">
         <option value="">[Month]</option>
<?php
   for($j=1; $j<=12; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($month==$j) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$j]; ?></option>
<?php	
   }
?>
      </select> 
      <select name="eaccident_day_<?php echo $accident['accident_id']; ?>" id="eaccident_day_<?php echo $accident['accident_id']; ?>">
         <option value="">[Day]</option>
<?php
   for($j=1; $j<=31; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($day==$j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php	
   }
?>
      </select> 
      <input type="text" name="eaccident_year_<?php echo $accident['accident_id']; ?>" id="eaccident_year_<?php echo $accident['accident_id']; ?>" value="<?php echo $year; ?>" style="width:50px; margin-right: 10px;" maxlength="4" /> 
      
      <a href="javascript:confirm_deletion(2, <?php echo $accident['accident_id']; ?>, '<?php echo getSelf(); ?>?rid=<?php echo $report_id; ?>', 'accident', 1);">Delete</a>
   </div>
</div><!-- div.accident -->
