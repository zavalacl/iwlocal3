<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
		
	$report_id = (int) $_GET['rid'];
	$report_info = getStewardsReport($report_id);
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
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css" media="print">
#accidents, #accidents-h2 {
	display: none;
}
</style>
<link type="text/css" rel="stylesheet" href="/js/fancybox/fancybox.css" media="screen" />
<script type="text/javascript" src="/js/fancybox/fancybox.js"></script>
<script type="text/javascript">
$(function(){
	
	// Photos lightbox gallery
	$('ul.photos a').fancybox({titlePosition: 'inside'});
	
});
</script>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<ul class="icon_options">
      		<li><a href="../get_excel.php?stewards_report&amp;rid=<?php echo $report_id; ?>"><img src="/img/icon_excel.gif" alt="Excel" /> Download Spreadsheet</a><br /></li>
         	<li><a href="/download.php?id=<?php echo $report_id; ?>&amp;t=stewards_reports"><img src="/img/icon_pdf.gif" alt="PDF" /> Download PDF</a></li>
         	<li><a href="/download.php?id=<?php echo $report_id; ?>&amp;t=stewards_reports&amp;accidents"><img src="/img/icon_pdf.gif" alt="PDF" /> Download PDF <em>with</em> Accidents</a></li>
         </ul>
         
      	<h1>Admin: Steward's Reports: View Report</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <div id="stewards_report">
         <div class="row">
         		<div class="block half">
            	<strong>Submitted on <?php echo date('F j, Y \a\t g:ia', strtotime($report_info['date_submitted'])); ?></strong>
            </div>
            <div class="block half">
            	<span class="label">Report IDs:</span> <?php echo $report_info['report_id']; ?> / <?php echo $report_info['id']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
         	<div class="block full">
            	<span class="label">Type: </span> <?php echo printStewardsReportType($report_info['type']); ?> Report
            </div>
         </div><!-- div.row -->
         <div class="row">
         	<div class="block full">
               <span class="label">Name/Description of Project</span><br /> 
               <?php echo $report_info['project_name']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block">
               <span class="label">Project Address/Location</span><br /> 
               <?php echo $report_info['project_location']; ?>
            </div>
            <div class="block">
               <span class="label">City</span><br /> 
               <?php echo $report_info['project_city']; ?>
            </div>
            <div class="block">
               <span class="label">County</span><br /> 
               <?php echo $report_info['project_county']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block">
               <span class="label">Project Start Date</span><br /> 
               <?php echo ($report_info['project_start_date']) ? date('F j, Y', strtotime($report_info['project_start_date'])) : ''; ?>
            </div>
            <div class="block">
               <div class="label">Pay Period Start</div> 
               <?php echo ($report_info['pay_period_start']) ? date('F j, Y', strtotime($report_info['pay_period_start'])) : ''; ?>
            </div>
            <div class="block">
               <div class="label">Pay Period End</div> 
               <?php echo date('F j, Y', strtotime($report_info['pay_period_ending'])); ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
         	<div class="block half">
               <span class="label">Company - In which your are employed</span><br /> 
               <?php echo $report_info['company']; ?>
            </div>
            <div class="block half">
               <span class="label">General Contractor</span><br /> 
               <?php echo $report_info['general_contractor']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block">
               <span class="label">Duration of Job</span><br /> 
               <?php echo $report_info['job_duration']; ?>
            </div>
            <div class="block">
               <span class="label">% of Completion</span><br /> 
               <?php echo $report_info['percent_completed']; ?>%
            </div>
            <div class="block">
               <span class="label">Contractors Welfare/Pension Paid Up?</span><br /> 
               <?php echo ($report_info['contractor_welfare_paid']) ? 'Yes' : 'No'; ?>
               <?php if(!$report_info['contractor_welfare_paid']){ ?> &nbsp; <span class="label">Paid to:</span> <?php echo $month_list[$report_info['contractor_welfare_paid_to_month']]; } ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
         		<div class="block">
               <span class="label">Total No. of Iron Workers on this Report</span><br /> 
               <?php echo $report_info['num_ironworkers']; ?>
            </div>
            <div class="block">
               <span class="label">Shift</span><br /> 
               <?php echo $report_info['shift']; ?>
            </div>
            <div class="block">
               <span class="label">Journeymen Wages on this Project Paid at</span><br /> 
               <?php echo $report_info['journeymen_wages_paid']; ?>
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <span class="label">Contact Phone Number</span><br /> 
               <?php echo formatPhone($report_info['job_phone']); ?>
            </div>
            <div class="block">
               <span class="label">Job Funding</span><br /> 
<?php
			switch($report_info['job_funding']){
			case JOB_FUNDING_STATE :
				echo 'State';
				break;
			case JOB_FUNDING_FEDERAL :
				echo 'Federal';
				break;
			case JOB_FUNDING_PRIVATE :
				echo 'Private';
				break;
			}
?>
            </div>
            <div class="block">
               
            </div>
         </div><!-- div.row -->
<?php
		$foremen = getStewardsReportForemen($report_id);
		if($foremen > 0){
			
			$foremen_bunch = array();
			foreach($foremen as $foreman){
				$foremen_bunch[] = $foreman['first_name'].' '.$foreman['last_name'];
			}
?>
         <div class="row">
         		<div class="block full">
               <span class="label">Foremen</span><br /> 
               <?php echo implode(', ', $foremen_bunch); ?>
            </div>
         </div><!-- .row -->
<?php
		}
?>

         <div class="row">
            <div class="block full">
               <span class="label">Description of Work</span><br /> 
               <?php echo nl2br($report_info['job_description']); ?>
            </div>
         </div><!-- div.row -->
         
         
         <br style="clear:left;">
         
<?php
		$accidents = getStewardsReportAccidents($report_id);
		if($accidents > 0){
?>         
         <h2 id="accidents-h2">Accidents</h2>
         <div id="accidents">
         	<div class="accident head">
               <div class="description">Description of Accident</div>
               <div class="date">Date of Accident</div>
            </div>
<?php
			foreach($accidents as $accident){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
?>
				<div class="accident" style="background-color:<?php echo $bkgd; ?>;">
               <div class="description"><?php echo nl2br($accident['description']); ?></div>
               <div class="date"><?php echo date('F j, Y', strtotime($accident['date'])); ?></div>
            </div>
<?php
			}
?>
         </div><!-- div#accidents -->
         
         <br style="clear:left;">
<?php
		}
?>         
         
         <h2>Workers</h2>
         <div id="workers">
         		<table class="small">
         			<thead>
	         			 <th>&nbsp;</th>
               	 <th>Name</th>
	               <th>Local</th>
	               <th>Type</th>
	               <th>Book No.</th>
	               <th>Month Paid</th>
	               <th>Hrs. Worked</th>
	               <th>Hrs. Paid</th>
	               <th>Str. Time</th>
	               <th>Time & 1/2</th>
	               <th>Dbl. Time</th>
            </thead>
            <tbody>
<?php
		$workers = getStewardsReportWorkers($report_id);
		if($workers > 0){
			
			$total_hours_worked = 0;
			$total_hours_paid = 0;
			$i=1;
			foreach($workers as $worker){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
				$total_hours_worked += $worker['hours_worked'];
				$total_hours_paid += $worker['hours_paid'];
				$book_number = ($worker['is_ssn']) ? '*********' : $worker['book_number'];
?>
						<tr style="background-color:<?php echo $bkgd; ?>;">
							 <td><?php echo $i; ?></td>
               <td><?php echo $worker['first_name'].' '.$worker['last_name']; ?></td>
               <td><?php echo $worker['local_number']; ?></div>
               <td><?php echo printWorkerType($worker['type']); ?></td>
               <td><?php echo $book_number; ?></td>
               <td><?php echo $worker['month_dues_paid']; ?></td>
               <td><?php echo $worker['hours_worked']; ?></td>
               <td><?php echo $worker['hours_paid']; ?></td>
               <td><?php echo $worker['time_straight']; ?></td>
               <td><?php echo $worker['time_half']; ?></td>
               <td><?php echo $worker['time_double']; ?></td>
            </tr>
<?php
				$i++;
			}
		}
?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="6">&nbsp;</td>
								<td><?php echo $total_hours_worked; ?></td>
								<td><?php echo $total_hours_paid; ?></td>
								<td colspan="3">&nbsp;</td>
							</tr>
						</tfoot>
					</table>
					
         </div><!-- div#workers -->
         
         
         <br style="clear:left;">
         
<?php
		$photos = getStewardsReportPhotos($report_id);
		if($photos > 0){
?>        
         <h2>Job Site Photos</h2>
         <div class="row">
         	<ul class="photos">
<?php
			foreach($photos as $photo){
?>
						<li><a href="<?php echo new Image($photo['filename'], 1000, 900, 'fit', 'stewards_reports_photos'); ?>" title="<?php echo htmlentities($photo['caption'], ENT_QUOTES); ?>" rel="photos"><img src="<?php echo new Image($photo['filename'], 400, 100, 'fit', 'stewards_reports_photos'); ?>" alt="" /></a></li>
<?php
			}
?>
         	</ul>
         </div><!-- .row -->
         
         <br style="clear: left;">
<?php
		}
?>
         
         <div class="row">
         	<div class="block quarter">
               <span class="label">Steward's Name</span><br />
              <?php echo $report_info['steward_name']; ?>
            </div>
            <div class="block quarter">
               <span class="label">Address</span><br /> 
               <?php echo $report_info['steward_address']; ?>
            </div>
            <div class="block quarter">
               <span class="label">Phone</span><br /> 
               <?php echo formatPhone($report_info['steward_phone']); ?>
            </div>
            <div class="block quarter">
               <span class="label">Email</span><br /> 
               <?php echo $report_info['steward_email']; ?><br />
               Join Email List: <?php echo ($report_info['steward_email_subscribe']) ? 'Yes' : 'No'; ?>
            </div>
         </div><!-- div.row -->
         </div><!-- div#stewards_report -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>