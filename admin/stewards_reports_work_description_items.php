<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	// Added item?
	if(isset($_GET['ni'])) $alerts->addAlert('The item was successfully added.', 'success');
	
	
	// Selected category
	$category_id = (int) $_GET['cid'];
	$category_info = getWorkDescriptionCategory($category_id);
	
	
	// Selected Subcategory
	if(!empty($_GET['sid'])){
		$subcategory_id = (int) $_GET['sid'];
		$subcategory_info = getWorkDescriptionItem($subcategory_id);
	} else {
		$subcategory_id = false;
	}
	
	
	// Delete a work description item
	if(isset($_GET['d1'])){
		$item_id = (int) $_GET['d1'];
		if(deleteWorkDescriptionItem($item_id) > 0)
			$alerts->addAlert('The item was successfully deleted.', 'success');
		else
			$alerts->addAlert('The item could not be deleted.');
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
         	<li><a href="stewards_reports_work_description_categories.php">Categories</a></li>
         	<li><a href="stewards_reports_work_description_items.php?cid=<?php echo $category_id; ?>"><?php echo $category_info['category']; ?></a></li>
<?php
			if($subcategory_id){
				$subcategories = getWorkDescriptionItemsParentsRecursive($subcategory_id);
				if($subcategories > 0){
					foreach($subcategories as $subitem_id){
						$item_info = getWorkDescriptionItem($subitem_id);
?>
					<li><a href="stewards_reports_work_description_items.php?cid=<?php echo $category_id; ?>&amp;sid=<?php echo $subitem_id; ?>"><?php echo $item_info['item']; ?></a></li>
<?php
					}
				}
?>
					<li><?php echo $subcategory_info['item']; ?></li>
<?php
			}
?>
        </ul>
      
      	<a href="stewards_reports_work_description_items_add.php?cid=<?php echo $category_id; ?>" class="icon_add">Add Item</a>
         
      	<h1>Admin: Stewards Reports: Work Description Items</h1>
      	<h2><?php echo $category_info['category']; ?></h2>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         		<tr>
         			<th>Item</th>
              <th width="60">&nbsp;</th>
            </tr>
<?php
			$items = getWorkDescriptionItems($category_id, $subcategory_id);
			if($items > 0){
				foreach($items as $item){
					$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	 <td>
            	 	<?php echo $item['item']; ?><br />
            	 	<small><em><?php echo getWorkDescriptionTreeAsString($item['item_id'], $category_id); ?></em></small>
            	 </td>
               <td>
<?php
					// Sub-items?
					if(getWorkDescriptionItems($category_id, $item['item_id']) > 0){
?>
									<a href="stewards_reports_work_description_items.php?cid=<?php echo $category_id; ?>&amp;sid=<?php echo $item['item_id']; ?>" class="icon_view">Subcategories</a>
<?php
					}
?>
               		<a href="stewards_reports_work_description_items_edit.php?iid=<?php echo $item['item_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $item['item_id']; ?>,'<?php echo getSelf(); ?>?cid=<?php echo $category_id; ?>&amp;sid=<?php echo $subcategory_id; ?>','item',1);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
				}
			} else {
?>
				<tr>
            	<td colspan="2">There are currently no work description items in this category.</td>
            </tr>
<?php
			}
?>
         </table>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>