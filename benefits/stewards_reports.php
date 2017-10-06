<?php
	require('config.php');
	$access_level=ACCESS_LEVEL_BENEFITS_DEPT; require("authenticate.php");
	require('functions/stewards_reports.php');
	require_once('classes/Paginator.php');
		
	
	// Delete a Report
	/*
	if(isset($_GET['d1'])){
			$did = escapeData($_GET['d1']);
			if(deleteStewardsReport($did) > 0)
				$alerts->addAlert('The report was successfully deleted.');
			else
				$alerts->addAlert('The report could not be deleted.');
		}
	*/
	
	
	// Pagination
	$pages = new Paginator();
	$pages->items_total = countStewardsReports();
	$pages->mid_range = 9;
	$pages->items_per_page = 50;
	$pages->paginate();
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Benefits Department: Steward's Reports | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports'; include("subnav_benefits.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="../get_excel.php?stewards_reports" class="icon_excel">Download Spreadsheet</a>
         
      	<h1>Benefits Department: Steward's Reports</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
            	<th>ID</th>
               <th>Project Name</th>
               <th>Steward Name</th>
               <th>Submitted</th>
               <th width="38">&nbsp;</th>
            </tr>
<?php
		$reports = getStewardsReports($pages->limit);
		if($reports > 0){
			foreach($reports as $report){
				$class = ($class=='odd') ? 'even' : 'odd';
?>
            <tr class="<?php echo $class; ?>">
            	<td><?php echo $report['report_id']; ?>/<?php echo $report['id']; ?></td>
               <td><?php echo $report['project_name']; ?></td>
               <td><?php echo $report['steward_name']; ?></td>
               <td><?php echo date('n/j/Y', strtotime($report['date_submitted'])); ?></td>
               <td>
               	<a href="stewards_reports_view.php?rid=<?php echo $report['report_id']; ?>" title="View" class="icon_view">View</a>
                  <a href="/download.php?id=<?php echo $report['report_id']; ?>&amp;t=stewards_reports" title="Download PDF" class="icon_pdf">Download PDF</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="5">There are currently no steward's reports.</td>
            </tr>
<?php
		}
?>
         </table>
         

<?php
			// Pagination
			if($pages->num_pages > 1){ 
?>	
        <div class="pagination">
           <?php echo $pages->display_pages(); ?>
        </div>
<?php	
   		}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>