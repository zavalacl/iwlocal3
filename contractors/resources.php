<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_CONTRACTOR; require("authenticate.php");
	require('functions/contractors.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Contractors: Resources | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<style type="text/css">
#documents {
	list-style:none;
	margin:0;
	padding:0;
}
#documents li {
	padding-bottom:15px;
}
#documents li .description {
	font-size:.9em;
}
</style>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='resources'; include("subnav_contractor.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>Contractors: Resources</h1>
         
         <ul id="documents">
<?php
		$documents = getContractorDocuments();
		if($documents > 0){
			foreach($documents as $document){
?>
				<li><a href="/download.php?id=<?php echo $document['document_id']; ?>&amp;t=contractor_documents"><?php echo $document['title']; ?></a>
            	<?php echo ($document['description']) ? '<br /><span class="description">'.nl2br($document['description']).'</span>' : ''; ?>
            </li>
<?php
			}
		} else {
?>
				<li>There are currently no available resources.</li>
<?php
		}
?>
         </ul>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>