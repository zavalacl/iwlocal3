<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	$item_id = (int) $_GET['iid'];
	
	
	// Edit item
	if(isset($_POST['submit'])){
		try {
			$required = array('item');
			
			$validator = new Validator($required);
			$validator->noFilter('item');
			$validator->isInt('describe');

			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				if(editWorkDescriptionItem($item_id, $clean['item'], $clean['describe'])){
					$alerts->addAlert('The item was successfully updated.', 'success');
				} else {
					$alerts->addAlert('The item could not be updated.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Get item info
	$item_info = getWorkDescriptionItem($item_id);
	$category_id = $item_info['category_id'];
	
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
         	<li>Edit Item</li>
         </ul>
         
      	<h1>Admin: Stewards Reports: Edit Work Description Item</h1>
         
        <?php if($alerts->hasAlerts()) echo $alerts; ?>
        
        <p>Use the form below to update the work description item for Steward's Reports.</p>
         
				<form action="<?php echo getSelf(); ?>?iid=<?php echo $item_id; ?>" method="post" enctype="multipart/form-data">
         	<p>
         		<label for="item"><span class="required">*</span> Item</label> 
         		<input type="text" name="item" id="item" value="<?php echo htmlentities($item_info['item'], ENT_QUOTES); ?>" maxlength="255" />
         	</p>
         	<p>
         		<div class="label">&nbsp;</div>
         		<input type="checkbox" name="describe" id="describe" value="1" class="checkbox"<?php echo ($item_info['describe']) ? ' checked="checked"' : ''; ?> /> 
         		<label for="describe" class="normal">Allow description to be entered.</label>
         	</p>
          <p class="submit"><input type="submit" name="submit" id="submit" value="Update Item" /></p>
        </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>