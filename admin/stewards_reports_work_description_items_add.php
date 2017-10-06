<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	// Selected category
	$category_id = (!empty($_GET['cid'])) ? (int) $_GET['cid'] : (int) $_POST['category_id'];
	
	
	// Add item
	if(isset($_POST['submit'])){
		try {
			$required = array('category_id', 'item');
			
			$validator = new Validator($required);
			$validator->isInt('category_id'); $validator->addAlias('category_id', 'Category');
			$validator->isInt('subcategory_id'); $validator->addAlias('subcategory_id', 'Subcategory');
			$validator->noFilter('item');
			$validator->isInt('describe');

			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				$item_id = newWorkDescriptionItem($clean['category_id'], $clean['subcategory_id'], $clean['item'], $clean['describe']);
				if($item_id > 0){
				
					header('Location: stewards_reports_work_description_items.php?ni&cid='.$category_id);
					exit;
					
				} else {
					$alerts->addAlert('The item could not be added.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Stewards Reports: Work Descriptions | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css">
#subcategories {
	width: 770px;
}
</style>
<script type="text/javascript">
function getWorkDescriptionItems(category_id, subcategory_id){
	
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: '../inc/requests/getWorkDescriptionItems.php',
		data: 'json=true&cid='+category_id+'&sid='+subcategory_id,
		success: function(data){
			if(data){
			
				// New subcategory selection
				var items = [];
				
				$.each(data, function(val, item) {
					items.push('<option value="' + val + '">' + item + '</option>');
				});
				
				$('#subcategories').append('<p><select onchange="setNewSubcategoryInput(this, '+category_id+');"><option value=""></option>'+items+'</select></p>');
			}
		}
	});
}

function setNewSubcategoryInput(input, category_id){

	// Only if it's not null and not already the current item
	if(input.value != '' && !$(input).hasClass('subcategory')){
	
		// Get sub-categories/items
		getWorkDescriptionItems(category_id, input.value);
	
		// Disable previous selections
		$('select.subcategory').attr('disabled', 'disabled').attr('name', '');
		
		// Assign selected input as subcategory_id
		$(input).attr('name', 'subcategory_id').addClass('subcategory');
	}
}
</script>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_work_descriptions'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="stewards_reports_work_description_items.php?cid=<?php echo $category_id; ?>">Work Description Items</a></li>
         	<li>Add Item</li>
         </ul>
         
      	<h1>Admin: Stewards Reports: Add Work Description Item</h1>
         
        <?php if($alerts->hasAlerts()) echo $alerts; ?>
        
        <p>Use the form below to add a new work description item for Steward's Reports.</p>
         
				<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
         	<p>
         		<label for="category"><span class="required">*</span> Category</label>
         		<select name="category_id" id="category_id" onchange="if(this.value!=''){ $('#subcategories').html(''); getWorkDescriptionItems(this.value, 0); }">
         			<option value=""></option>
<?php
			$categories = getWorkDescriptionCategories();
			if($categories > 0){
				foreach($categories as $category){
?>
							<option value="<?php echo $category['category_id']; ?>"<?php echo ($category_id==$category['category_id']) ? ' selected="selected"' : ''; ?>><?php echo $category['category']; ?></option>
<?php
				}
			}
?>
         		</select> 
         		<a href="javascript:;" onclick="$('#category_id').val(''); $('#subcategories').html('');">Clear</a>
         	</p>
         	<div id="subcategories">
<?php
			$subcategories = getWorkDescriptionItems($category_id);
			if($subcategories > 0){
?>
						<p>
							<select name="subcategory_id" onchange="setNewSubcategoryInput(this, <?php echo $category_id; ?>);">
								<option value=""></option>
<?php
				foreach($subcategories as $subcategory){
?>
								<option value="<?php echo $subcategory['item_id']; ?>"><?php echo $subcategory['item']; ?></option>
<?php
				}
?>
							</select>
						</p>
<?php
			}
?>         		
         	</div> <!-- #subcategories -->
         	<p>
         		<label for="item"><span class="required">*</span> Item</label> 
         		<input type="text" name="item" id="item" value="<?php echo htmlentities($_POST['item'], ENT_QUOTES); ?>" maxlength="255" />
         	</p>
         	<p>
         		<div class="label">&nbsp;</div>
         		<input type="checkbox" name="describe" id="describe" value="1" class="checkbox"<?php echo ($_POST['describe']) ? ' checked="checked"' : ''; ?> /> 
         		<label for="describe" class="normal">Allow description to be entered.</label>
         	</p>
          <p class="submit"><input type="submit" name="submit" id="submit" value="Add Item" /></p>
        </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>