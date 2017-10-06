<?php
	require_once('constants.php');
	$bkgd = ($bkgd) ? $bkgd : $_POST['bkgd'];
	$i = ($i) ? $i : $_POST['i'];
?>
<div class="foreman" style="background-color:<?php echo $bkgd; ?>;">
	<div class="num"><?php echo $i; ?></div>
	<div class="first_name">
		<input type="text" name="eforeman_first_name_<?php echo $foreman['foreman_id']; ?>" id="eforeman_first_name_<?php echo $foreman['foreman_id']; ?>" value="<?php echo htmlentities($foreman['first_name'], ENT_QUOTES); ?>" />
	</div> <!-- .first_name -->
	<div class="last_name">
		<input type="text" name="eforeman_last_name_<?php echo $foreman['foreman_id']; ?>" id="eforeman_last_name_<?php echo $foreman['foreman_id']; ?>" value="<?php echo htmlentities($foreman['last_name'], ENT_QUOTES); ?>" />
	</div> <!-- .last_name -->
</div><!-- div.foreman -->