<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
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
<title>Steward's Reports | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_report_search'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<ul class="icon_options">
      		<li><a href="../get_excel.php?stewards_report&amp;rid=<?php echo $report_id; ?>"><img src="/img/icon_excel.gif" alt="Excel" /> Download Spreadsheet</a><br /></li>
         	<li><a href="/download.php?id=<?php echo $report_id; ?>&amp;t=stewards_reports"><img src="/img/icon_pdf.gif" alt="PDF" /> Download PDF</a></li>
         </ul>
         
      	<h1>Steward's Reports: View Report</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <div id="stewards_report">
         <div class="row">
         	<div class="block full">
            	<strong>Submitted on <?php echo date('F j, Y \a\t g:ia', strtotime($report_info['date_submitted'])); ?></strong>
            </div>
         </div><!-- div.row -->
         <div class="row">
         	<div class="block full">
            	<span class="label">Type: </span> <?php echo printStewardsReportType($report_info['type']); ?> Report
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block">
               <span class="label">Location of Project</span><br /> 
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
               <span class="label">Name of Project</span><br /> 
               <?php echo $report_info['project_name']; ?>
            </div>
            <div class="block">
               <span class="label">Project Start Date</span><br /> 
               <?php echo date('F j, Y', strtotime($report_info['project_start_date'])); ?>
            </div>
            <div class="block">
               <div class="label">Pay Period Ending</div> 
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
            <div class="block" style="width:161px;">
               <span class="label">Duration of Job</span><br /> 
               <?php echo $report_info['job_duration']; ?>
            </div>
            <div class="block" style="width:161px;">
               <span class="label">% of Completion</span><br /> 
               <?php echo $report_info['percent_completed']; ?>%
            </div>
            <div class="block">
               <span class="label">Contractors Welfare/Pension Paid Up?</span><br /> 
               <?php echo ($report_info['contractor_welfare_paid']) ? 'Yes' : 'No'; ?>
               <?php if(!$report_info['contractor_welfare_paid']){ ?><br /><span class="label">Paid to:</span> <?php echo $month_list[$report_info['contractor_welfare_paid_to_month']]; } ?>
            </div>
            <div class="block" style="width:161px;">
               <span class="label">No. of Iron Workers on Job</span><br /> 
               <?php echo $report_info['num_ironworkers']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block">
               <span class="label">Job Phone</span><br /> 
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
               <span class="label">Foreman's Name</span><br /> 
               <?php echo $report_info['foreman_name']; ?>
            </div>
         </div><!-- div.row -->
         <div class="row">
            <div class="block full">
               <span class="label">Description of Work</span><br /> 
               <?php echo nl2br($report_info['job_description']); ?>
            </div>
         </div><!-- div.row -->
         
         
         <br style="clear:left;">
         
<?php
/*         
         <h2>Accidents</h2>
         <div id="accidents">
         	<div class="accident head">
               <div class="description">Description of Accident</div>
               <div class="date">Date of Accident</div>
            </div>
<?php
		$accidents = getStewardsReportAccidents($report_id);
		if($accidents > 0){
			foreach($accidents as $accident){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
?>
				<div class="accident" style="background-color:<?php echo $bkgd; ?>;">
               <div class="description"><?php echo nl2br($accident['description']); ?></div>
               <div class="date"><?php echo date('F j, Y', strtotime($accident['date'])); ?></div>
            </div>
<?php
			}
		}
?>
         </div><!-- div#accidents -->
*/
?>         
         
         <br style="clear:left;">
         
         
         <h2>Workers</h2>
         <div id="workers">
         	<div class="worker head">
               <div class="first_name">First Name</div>
               <div class="last_name">Last Name</div>
               <div class="local_number">Local No.</div>
               <div class="type">Type</div>
               <div class="book_number">Book No.</div>
               <div class="month_dues_paid">Month Paid</div>
               <div class="hours_paid">Total Hrs. Paid</div>
            </div>
<?php
		$workers = getStewardsReportWorkers($report_id);
		if($workers > 0){
			
			$total_hours = 0;
			foreach($workers as $worker){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
				$total_hours += $worker['hours_paid'];
				$book_number = ($worker['is_ssn']) ? '*********' : $worker['book_number'];
?>
				<div class="worker" style="background-color:<?php echo $bkgd; ?>;">
               <div class="first_name"><?php echo $worker['first_name']; ?></div>
               <div class="last_name"><?php echo $worker['last_name']; ?></div>
               <div class="local_number"><?php echo $worker['local_number']; ?></div>
               <div class="type"><?php echo printWorkerType($worker['type']); ?></div>
               <div class="book_number"><?php echo $book_number; ?></div>
               <div class="month_dues_paid"><?php echo $worker['month_dues_paid']; ?></div>
               <div class="hours_paid"><?php echo $worker['hours_paid']; ?></div>
            </div>
<?php
			}
		}
?>
				
         </div><!-- div#workers -->
         <div id="workers_total_hours">
            <div class="add_worker">
               &nbsp;
            </div>
            <div class="total_hours">
               <strong>Total Hours</strong> 
               <?php echo number_format($total_hours); ?>
            </div>
         </div><!-- div#workers_total_hours -->
         
         
         <br style="clear:left;">
         
         
         <div class="row">
         	<div class="block">
               <span class="label">Steward's Name</span><br />
              <?php echo $report_info['steward_name']; ?>
            </div>
            <div class="block">
               <span class="label">Address</span><br /> 
               <?php echo $report_info['steward_address']; ?>
            </div>
            <div class="block">
               <span class="label">Phone</span><br /> 
               <?php echo formatPhone($report_info['steward_phone']); ?>
            </div>
         </div><!-- div.row -->
         </div><!-- div#stewards_report -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>