<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/document_repository.php');
	
	if(isset($_GET['nd'])) $alerts->addAlert('The document was successfully added.', 'success');
	
	
	// Delete a Document
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteRepositoryDocument($did) > 0)
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
<title>Admin: Document Repository | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css">
table td {
	font-size:.9em;	
}
table td input {
	width:175px;
	background:#fff;
	border:1px solid #999;
}
a.comment {
	text-decoration:none;
	border-bottom:1px dotted;
}
</style>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='document_repository'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="document_repository_add.php" class="icon_add">Add Document</a>
         
      	<h1>Admin: Document Repository</h1>
         
         <p>This section allows you to upload documents for use throughout the website.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
            	<th>Title</th>
               <th>Filename</th>
               <th>Link Path</th>
               <th>Date</th>
               <th>DLs</th>
               <th width="40">&nbsp;</th>
            </tr>
<?php
		$documents = getRepositoryDocuments();
		if($documents > 0){
			foreach($documents as $document){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
            	<td><?php if($document['comment']){ ?><a href="javascript:;" title="<?php echo $document['comment']; ?>" class="comment"><?php } ?><?php echo $document['title']; ?><?php if($document['comment']){ ?></a><?php } ?></td>
               <td><?php echo $document['original_filename']; ?></td>
               <td><input type="text" name="path" value="<?php echo WEB_ROOT; ?>download.php?id=<?php echo $document['document_id']; ?>&amp;t=repos" onclick="this.focus(); this.select()" /></td>
               <td><?php echo date('n/j/Y', strtotime($document['date'])); ?></td>
               <td><?php echo number_format($document['downloads']); ?></td>
               <td>
                  <a href="document_repository_edit.php?did=<?php echo $document['document_id']; ?>" class="icon_edit">Edit</a>
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