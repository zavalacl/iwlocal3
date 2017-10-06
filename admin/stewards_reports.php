<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	require_once('classes/Pagination.php');
		
	
	// Delete a Report
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteStewardsReport($did) > 0)
			$alerts->addAlert('The report was successfully deleted.', 'success');
		else
			$alerts->addAlert('The report could not be deleted.');
	}
	
	
	// Pagination
	$reports_per_page = 50;
	$current_page = (!empty($_GET['p'])) ? (int) $_GET['p'] : 1;
	$total_reports = countStewardsReports();
	
	$pagination = new pagination();
	$pages = $pagination->calculate_pages($total_reports, $reports_per_page, $current_page);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Steward's Reports | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<!-- <a href="../get_excel.php?stewards_reports" class="icon_excel">Download Spreadsheet</a> -->
         
      	<h1>Admin: Steward's Reports</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
         	<tr>
         			<th>ID</th>
               <!-- <th>Project City</th> -->
               <th>Project Name</th>
               <!-- <th>Start Date</th> -->
               <th>Steward Name</th>
               <th>Submitted</th>
               <th>Flags</th>
               <th width="80">&nbsp;</th>
            </tr>
<?php
		$reports = getStewardsReports($pages['limit']);
		if($reports > 0){
			foreach($reports as $report){
				$class = ($class=='odd') ? 'even' : 'odd';
?>
            <tr class="<?php echo $class; ?>">
            	<td><?php echo $report['report_id']; ?>/<?php echo $report['id']; ?></td>
               <!-- <td><?php echo $report['project_city']; ?></td> -->
               <td><?php echo $report['project_name']; ?></td>
               <!-- <td><?php echo ($report['project_start_date']) ? date('n/j/Y', strtotime($report['project_start_date'])) : ''; ?></td> -->
               <td><?php echo $report['steward_name']; ?></td>
               <td><?php echo date('n/j/Y', strtotime($report['date_submitted'])); ?></td>
               <td>
<?php
				// Accidents?
				if($report['num_accidents'] > 0){
?>
								<img src="/img/icon_accident.gif" alt="Accidents" title="Accidents" /> 
<?php
				}
				
				// Photos?
				if($report['num_photos'] > 0){
?>
								<img src="/img/icon_images.gif" alt="Photos Attached" title="Photos Attached" /> 
<?php
				}
				
				// SSN's used?
				if($report['num_workers_ssn'] > 0){
?>
								<img src="/img/icon_lock.gif" alt="SSN Used" title="SSN Used" /> 
<?php
				}
				
				// Workers from other that local 3?
				if($report['num_workers_travelers'] > 0){
?>
								<img src="/img/icon_workers.gif" alt="Travelers" title="Travelers" />
<?php
				}
?>
               </td>
               <td>
               		<a href="stewards_reports_view<?php echo ($report['version'] != 2) ? '_v1' : ''; ?>.php?rid=<?php echo $report['report_id']; ?>" title="View" class="icon_view">View</a> 
               		<a href="stewards_reports_edit.php?rid=<?php echo $report['report_id']; ?>" title="Edit" class="icon_edit">Edit</a> 
                  <a href="/download.php?id=<?php echo $report['report_id']; ?>&amp;t=stewards_reports" title="Download PDF" class="icon_pdf">Download PDF</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $report['report_id']; ?>,'<?php echo getSelf(); ?>','report',0);" title="Delete" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
				<tr>
            	<td colspan="6">There are currently no steward's reports.</td>
            </tr>
<?php
		}
?>
         </table>
         
<?php		
					if(count($pages['pages']) > 1){
?>
						<div class="pagination">
<?php
						if($pages['previous']!=$current_page){
?>
							<a href="<?php echo getSelf(); ?>?p=<?php echo $pages['previous']; ?>" class="previous">&lt; Previous</a>
<?php
						}
?>
						<span class="info"><?php echo $pages['info']; ?></span>
<?php
						if($pages['next']!=$current_page){
?>
							<a href="<?php echo getSelf(); ?>?p=<?php echo $pages['next']; ?>" class="next">Next &gt;</a>
<?php
						}
?>
						</div><!-- div.pagination -->
<?php
					}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>