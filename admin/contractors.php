<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
	
	if(isset($_GET['nc'])) $alerts->addAlert('The contractor was successfully added.', 'success');
	
	// Delete a Contractor
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		
		if(deleteContractor($did) > 0)
			$alerts->addAlert('The contractor was successfully deleted.', 'success');
		else
			$alerts->addAlert('The contractor could not be deleted.');
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Contractors | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='contractors'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="contractors_upload.php" class="icon_add" style="margin-left: 15px;">Upload Contractors Spreadsheet</a>
      	<a href="contractors_add.php" class="icon_add">Add Contractor</a> 
      	
      	<h1>Admin: Contractors</h1>
                  
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
                  
         <table>
         	<thead>
               <tr>
                  <th width="90%">Name</th>
                  <th width="10%">&nbsp;</th>
               </tr>
            </thead>
            <tbody>
<?php
		$contractors = getContractors();
		if($contractors > 0){
			foreach($contractors as $contractor){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
               <tr style="background-color:<?php echo $bkgd; ?>;">
                  <td width="90%"><?php echo $contractor['name']; ?></td>
                  <td width="10%">
                     <a href="contractors_edit.php?cid=<?php echo $contractor['contractor_id']; ?>" class="icon_edit">Edit</a>
                     <a href="javascript:confirm_deletion(1,<?php echo $contractor['contractor_id']; ?>,'<?php echo getSelf(); ?>','contractor',0);" class="icon_delete">Delete</a>
                  </td>
               </tr>
<?php
			}
		} else {
?>
               <tr>
                  <td colspan="4">There are currently no contractors.</td>
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