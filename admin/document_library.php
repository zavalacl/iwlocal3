<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/document_library.php');
	
	if(isset($_GET['nd'])) $alerts->addAlert('The document was successfully added.', 'success');
	
	
	// Delete a Document
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteDocument($did) > 0)
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
<title>Admin: Document Library | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css">
table td {
	font-size:.9em;	
}
</style>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='document_library'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="document_library_add.php" class="icon_add">Add Document or Link</a>
         
      	<h1>Admin: Document Library</h1>
                  
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
            	<th>Title</th>
               <th>Filename or Link</th>
               <th>Date</th>
               <th>DLs</th>
               <th width="36">&nbsp;</th>
            </tr>
<?php
		$documents = getDocuments();
		if($documents > 0){
			foreach($documents as $document){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php echo $document['title']; ?></td>
               <td><?php echo ($document['original_filename']) ? $document['original_filename'] : '<a href="'.forceURI($document['url']).'">'.$document['url'].'</a>'; ?></td>
               <td><?php echo date('F j, Y', strtotime($document['date'])); ?></td>
               <td><?php echo ($document['original_filename']) ? number_format($document['downloads']) : 'N/A'; ?></td>
               <td>
               	<a href="document_library_edit.php?did=<?php echo $document['document_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $document['document_id']; ?>,'<?php echo getSelf(); ?>','document',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="3">There are currently no documents.</td>
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