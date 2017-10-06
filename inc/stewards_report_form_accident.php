<?php
	require_once('constants.php');
	$bkgd = ($bkgd) ? $bkgd : $_POST['bkgd'];
	$i = ($i) ? $i : $_POST['i'];
?>
<div class="accident" style="background-color:<?php echo $bkgd; ?>;">
   <div class="description"><textarea name="accident_description_<?php echo $i; ?>" id="accident_description_<?php echo $i; ?>" cols="50" rows="2"><?php echo htmlentities($_POST['accident_description_'.$i], ENT_QUOTES, 'utf-8'); ?></textarea></div>
   <div class="date">
      <select name="accident_month_<?php echo $i; ?>" id="accident_month_<?php echo $i; ?>">
         <option value="">[Month]</option>
<?php
	include_once('constants.php');
   for($j=1; $j<=12; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($_POST['accident_month_'.$i]==$j) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$j]; ?></option>
<?php	
   }
?>
      </select> 
      <select name="accident_day_<?php echo $i; ?>" id="accident_day_<?php echo $i; ?>">
         <option value="">[Day]</option>
<?php
   for($j=1; $j<=31; $j++){
?>
         <option value="<?php echo $j; ?>"<?php echo ($_POST['accident_day_'.$i]==$j) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php	
   }
?>
      </select> 
      <input type="text" name="accident_year_<?php echo $i; ?>" id="accident_year_<?php echo $i; ?>" value="<?php echo (!empty($_POST['accident_year_'.$i])) ? htmlentities($_POST['accident_year_'.$i], ENT_QUOTES, 'utf-8') : date('Y'); ?>" style="width:50px;" maxlength="4" />
   </div>
</div><!-- div.accident -->