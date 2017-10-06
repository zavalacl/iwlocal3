<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/announcements.php');
	
	if(isset($_GET['nd'])) $alerts->addAlert('The document was successfully added.', 'success');
	
	
	// Delete a Job Picture Document
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteJobPictureLink($did) > 0)
			$alerts->addAlert('The document was successfully deleted.', 'success');
		else
			$alerts->addAlert('The document could not be deleted.');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Announcements: Job Pictures | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='job_pictures'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="job_pictures_add.php" class="icon_add">Add Job Pictures Document</a>
         
      	<h1>Admin: Announcements: Job Pictures</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
            	<th>Title</th>
               <th>Filename</th>
               <th>Date</th>
               <th>DLs</th>
               <th width="36">&nbsp;</th>
            </tr>
<?php
		$documents = getJobPictureLinks();
		if($documents > 0){
			foreach($documents as $document){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php echo $document['title']; ?></td>
               <td><?php echo $document['original_filename']; ?></td>
               <td><?php echo date('F j, Y', strtotime($document['date'])); ?></td>
               <td><?php echo number_format($document['downloads']); ?></td>
               <td>
               	<a href="job_pictures_edit.php?did=<?php echo $document['document_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $document['document_id']; ?>,'<?php echo getSelf(); ?>','job pictures document',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="3">There are currently no job pictures documents.</td>
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