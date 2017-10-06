<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	if(isset($_GET['nc'])) $alerts->addAlert('The category was successfully added.', 'success');
	
	
	// Delete a work description category
	if(isset($_GET['d1'])){
		$category_id = (int) $_GET['d1'];
		if(deleteWorkDescriptionCategory($category_id) > 0)
			$alerts->addAlert('The category was successfully deleted.', 'success');
		else
			$alerts->addAlert('The category could not be deleted.');
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
      
      	<a href="stewards_reports_work_description_categories_add.php" class="icon_add">Add Category</a>
         
      	<h1>Admin: Stewards Reports: Work Description Categories</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <p>You can add new work description categories, or click on a category to add new 'items' to that category. Items can also be added to existing items to form a chain, getting more specific with each additional chain link.</p>
         
         <table>
         		<tr>
         			<th>Category</th>
              <th width="60">&nbsp;</th>
            </tr>
<?php
		$categories = getWorkDescriptionCategories();
		if($categories > 0){
			foreach($categories as $category){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	 <td><a href="stewards_reports_work_description_items.php?cid=<?php echo $category['category_id']; ?>"><?php echo $category['category']; ?></a></td>
               <td>
               		<a href="stewards_reports_work_description_items.php?cid=<?php echo $category['category_id']; ?>" class="icon_view">View</a> 
               		<a href="stewards_reports_work_description_categories_edit.php?cid=<?php echo $category['category_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $category['category_id']; ?>,'<?php echo getSelf(); ?>','category and ALL items it contains',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="2">There are currently no work description categories.</td>
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