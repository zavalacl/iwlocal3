<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/faqs.php');
	
	if(isset($_GET['nf'])) $alerts->addAlert('The FAQ was successfully added.', 'success');
	
	// Delete an FAQ
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteFaq($did) > 0)
			$alerts->addAlert('The FAQ was successfully deleted.', 'success');
		else
			$alerts->addAlert('The FAQ could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: FAQs | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
	$("#faqs").sortable({ opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize");
			$.post("/inc/requests/orderFaqs.php", order);
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
      	<?php $page='faqs'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="faqs_add.php" class="icon_add">Add FAQ</a>
      	
      	<h1>Admin: FAQs</h1>
         
         <p>Drag and drop FAQs to reorder.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
                  
         <table>
         	<thead>
               <tr>
                  <th width="90%">Question</th>
                  <th width="10%">&nbsp;</th>
               </tr>
            </thead>
            <tbody id="faqs">
<?php
		$faqs = getfaqs();
		if($faqs > 0){
			foreach($faqs as $faq){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
               <tr style="background-color:<?php echo $bkgd; ?>;" id="fids_<?php echo $faq['faq_id']; ?>">
                  <td width="90%"><?php echo $faq['question']; ?></td>
                  <td width="10%">
                     <a href="faqs_edit.php?fid=<?php echo $faq['faq_id']; ?>" class="icon_edit">Edit</a>
                     <a href="javascript:confirm_deletion(1,<?php echo $faq['faq_id']; ?>,'<?php echo getSelf(); ?>','faq',0);" class="icon_delete">Delete</a>
                  </td>
               </tr>
<?php
			}
		} else {
?>
               <tr>
                  <td colspan="4">There are currently no faqs.</td>
               </tr>
<?php
		}
?>
         	</tbody>
         </table>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>