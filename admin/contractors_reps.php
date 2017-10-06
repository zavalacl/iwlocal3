<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
	
	if(isset($_GET['nr'])) $alerts->addAlert('The contractor rep was successfully added.', 'success');
	
	// Delete a rep
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		
		if(deleteContractorRep($did) > 0)
			$alerts->addAlert('The contractor rep was successfully deleted.', 'success');
		else
			$alerts->addAlert('The contractor rep could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Contractor Reps | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
	$("tbody.reps").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize");
			$.post("/inc/requests/orderContractorReps.php", order);
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
      	<?php $page='contractors_reps'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
            	
      	<h1>Admin: Contractor Reps</h1>
         
         <p>Drag and drop reps to reorder.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
                  <?php
		
		$territories = getContractorRepTerritories();
		if($territories > 0){
			foreach($territories as $territory){
?>
					<a href="contractors_reps_add.php?tid=<?php echo $territory['territory_id']; ?>" class="icon_add">Add Rep</a>
					<h2><?php echo $territory['territory']; ?></h2>
					
					<table>
         		<thead>
               <tr>
                  <th width="45%">Name</th>
                  <th width="45%">Title</th>
                  <th width="10%">&nbsp;</th>
               </tr>
            </thead>
            <tbody class="reps">
<?php
				$reps = getContractorReps($territory['territory_id']);
				if($reps > 0){
					foreach($reps as $rep){
						$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
               <tr style="background-color:<?php echo $bkgd; ?>;" id="rids_<?php echo $rep['rep_id']; ?>">
                  <td width="45%"><?php echo $rep['first_name'].' '.$rep['last_name']; ?></td>
                  <td width="45%"><?php echo $rep['title']; ?></td>
                  <td width="10%">
                     <a href="contractors_reps_edit.php?rid=<?php echo $rep['rep_id']; ?>" class="icon_edit">Edit</a>
                     <a href="javascript:confirm_deletion(1,<?php echo $rep['rep_id']; ?>,'<?php echo getSelf(); ?>','contractor rep',0);" class="icon_delete">Delete</a>
                  </td>
               </tr>
<?php
					}
				} else {
?>
               <tr>
                  <td colspan="3">There are currently no reps in this territory.</td>
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