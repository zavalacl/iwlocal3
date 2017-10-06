<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/officers.php');
	
	if(isset($_GET['no'])) $alerts->addAlert('The officer was successfully added.', 'success');
	
	// Delete an officer
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		
		if(deleteOfficer($did) > 0)
			$alerts->addAlert('The officer was successfully deleted.', 'success');
		else
			$alerts->addAlert('The officer could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Officers | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css">
tbody td {
	cursor:move;
}
</style>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function() {
	$("tbody.officers").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize");
			$.post("/inc/requests/orderOfficers.php", order);
		}
	});
});
</script>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='officers'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
            	
      	<h1>Admin: Officers</h1>
         
         <p>Drag and drop officers to reorder.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
                  <?php
		
		$categories = getOfficerCategories();
		if($categories > 0){
			foreach($categories as $category){
?>
					<a href="officers_add.php?cid=<?php echo $category['category_id']; ?>" class="icon_add">Add Officer</a>
					<h2><?php echo $category['category']; ?></h2>
					
					<table>
         		<thead>
               <tr>
                  <th width="45%">Name</th>
                  <th width="45%">Title</th>
                  <th width="10%">&nbsp;</th>
               </tr>
            </thead>
            <tbody class="officers">
<?php
				$officers = getOfficers($category['category_id']);
				if($officers > 0){
					foreach($officers as $officer){
						$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
               <tr style="background-color:<?php echo $bkgd; ?>;" id="oids_<?php echo $officer['officer_id']; ?>">
                  <td width="45%"><?php echo $officer['first_name'].' '.$officer['last_name']; ?></td>
                  <td width="45%"><?php echo $officer['title']; ?></td>
                  <td width="10%">
                     <a href="officers_edit.php?oid=<?php echo $officer['officer_id']; ?>" class="icon_edit">Edit</a>
                     <a href="javascript:confirm_deletion(1,<?php echo $officer['officer_id']; ?>,'<?php echo getSelf(); ?>','officer',0);" class="icon_delete">Delete</a>
                  </td>
               </tr>
<?php
					}
				} else {
?>
               <tr>
                  <td colspan="3">There are currently no officers in this category.</td>
               </tr>
<?php
				}
?>
         	</tbody>
         </table>
         
         <hr>
<?php
			}
		}
?>
         
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>