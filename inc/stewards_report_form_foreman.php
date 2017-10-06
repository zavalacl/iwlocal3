<?php
	require_once('constants.php');
	$bkgd = ($bkgd) ? $bkgd : $_POST['bkgd'];
	$i = ($i) ? $i : $_POST['i'];
?>
<div class="foreman" style="background-color:<?php echo $bkgd; ?>;">
	<div class="num"><?php echo $i; ?></div>
	<div class="first_name">
		<input type="text" name="foreman_first_name_<?php echo $i; ?>" id="foreman_first_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['foreman_first_name_'.$i], ENT_QUOTES); ?>" />
	</div> <!-- .first_name -->
	<div class="last_name">
		<input type="text" name="foreman_last_name_<?php echo $i; ?>" id="foreman_last_name_<?php echo $i; ?>" value="<?php echo htmlentities($_POST['foreman_last_name_'.$i], ENT_QUOTES); ?>" />
	</div> <!-- .last_name -->
</div><!-- div.foreman -->