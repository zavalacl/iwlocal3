<?php
	require_once('config.php');
	require_once('functions/stewards_reports.php');
	
	$i = ($i) ? $i : $_POST['i'];
?>
<div class="work_description">
   <label for="work_description_category_<?php echo $i; ?>">Description of Work</label> 
   <input type="hidden" name="wdv_<?php echo $i; ?>" id="wdv_<?php echo $i; ?>" value="<?php echo isset($_POST['wdv_' . $i]) ? (int) $_POST['wdv_' . $i] : ''; ?>" />
   <input type="hidden" name="wdv_other_<?php echo $i; ?>" id="wdv_other_<?php echo $i; ?>" value="" />
   <input type="hidden" name="wdv_describe_<?php echo $i; ?>" id="wdv_describe_<?php echo $i; ?>" value="" />
   <select 
        name="work_description_category_<?php echo $i; ?>" 
        id="work_description_category_<?php echo $i; ?>" 
        onchange="$('#wdb_<?php echo $i; ?>').empty(); getWorkDescriptionItems(this.value, null, 'wdb_<?php echo $i; ?>', <?php echo $i; ?>);"
    >
        <option value="">Select One</option>
<?php
	$categories = getWorkDescriptionCategories();
	if($categories > 0){
		foreach($categories as $category){
?>
   	    <option value="<?php echo $category['category_id']; ?>"<?php echo ($_POST['work_description_category_' . $i] == $category['category_id']) ? ' selected="selected"' : ''; ?>><?php echo htmlentities($category['category'], ENT_QUOTES); ?></option>
<?php	
		}
	}
?>
   </select>
   
   <div id="wdb_<?php echo $i; ?>"></div>
<?php
	if($_POST['work_description_category_'.$i]){
?>
	<script type="text/javascript">
		getWorkDescriptionItems(<?php echo (int) $_POST['work_description_category_'.$i]; ?>, null, 'wdb_<?php echo $i; ?>', <?php echo $i; ?>, <?php echo isset($_POST['wdv_' . $i]) ? (int) $_POST['wdv_' . $i] : 'null'; ?>);
	</script>
<?php
	}
?>
</div><!-- div.work_description -->
