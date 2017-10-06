<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_ADMIN; require("authenticate.php");
	require('functions/stewards_reports.php');
	require('functions/contractors.php');
	
	// Default values
	$num_foremen = (!empty($_POST['nf'])) ? (int) $_POST['nf'] : 0; // Number of foremen
	$num_accidents = (!empty($_POST['na'])) ? (int) $_POST['na'] : 0; // Number of accident rows
	$num_workers = (!empty($_POST['nw'])) ? (int) $_POST['nw'] : 0; // Number of worker rows
	$max_photo_uploads = 6; // Max number of images that can be uploaded
	
	
	// Report ID
	$report_id = (int) $_GET['rid'];
	
	
	// Submit Form to edit Steward's Report
	if(isset($_POST['submit'])){
		try {
			
			// Required fields
			$required = array('type', 'project_location', 'project_city', 'project_county', 'project_name', 'project_start_date', 'pay_period_start', 'pay_period_ending', 'company', 'general_contractor', 'job_duration', 'percent_completed', 'num_ironworkers', 'job_phone', 'job_funding', 'steward_name', 'steward_address', 'steward_phone');
			
			if($_POST['contractor_welfare_paid']==0) $required[] = 'contractor_welfare_paid_to_month';
			
			
			// Edit Foremen
			$foremen = getStewardsReportForemen($report_id);
			if($foremen > 0){
				foreach($foremen as $foreman){
					$required[] = 'eforeman_first_name_'.$foreman['foreman_id'];
					$required[] = 'eforeman_last_name_'.$foreman['foreman_id'];
				}
			}
			
			// Edit Accidents
			$accidents = getStewardsReportAccidents($report_id);
			if($accidents > 0){
				foreach($accidents as $accident){
					$required[] = 'eaccident_description_'.$accident['accident_id'];
					$required[] = 'eaccident_month_'.$accident['accident_id'];
					$required[] = 'eaccident_day_'.$accident['accident_id'];
					$required[] = 'eaccident_year_'.$accident['accident_id'];
				}
			}
			
			// Edit Workers
			$workers = getStewardsReportWorkers($report_id);
			if($workers > 0){
				foreach($workers as $worker){
					$required[] = 'eworker_first_name_'.$worker['worker_id'];
					$required[] = 'eworker_last_name_'.$worker['worker_id'];
					$required[] = 'eworker_local_number_'.$worker['worker_id'];
					$required[] = 'eworker_type_'.$worker['worker_id'];
					$required[] = 'eworker_book_number_'.$worker['worker_id'];
					$required[] = 'eworker_month_dues_paid_month_'.$worker['worker_id'];
					$required[] = 'eworker_month_dues_paid_year_'.$worker['worker_id'];
					$required[] = 'eworker_hours_worked_'.$worker['worker_id'];
					$required[] = 'eworker_hours_paid_'.$worker['worker_id'];
				}
			}
			
			
			
			// Add Foremen
			for($i=1; $i<=$num_foremen; $i++){
				if(!empty($_POST['foreman_first_name_'.$i])){
					$required[] = 'foreman_first_name_'.$i;
					$required[] = 'foreman_last_name_'.$i;
				}
			}
			
			// Add Accidents
			for($i=1; $i<=$num_accidents; $i++){
				if(!empty($_POST['accident_description_'.$i])){
					$required[] = 'accident_description_'.$i;
					$required[] = 'accident_month_'.$i;
					$required[] = 'accident_day_'.$i;
					$required[] = 'accident_year_'.$i;
				}
			}
			
			// Add Workers
			for($i=1; $i<=$num_workers; $i++){
				if(!empty($_POST['worker_first_name_'.$i])){
					$required[] = 'worker_first_name_'.$i;
					$required[] = 'worker_last_name_'.$i;
					$required[] = 'worker_local_number_'.$i;
					$required[] = 'worker_type_'.$i;
					$required[] = 'worker_book_number_'.$i;
					$required[] = 'worker_month_dues_paid_month_'.$i;
					$required[] = 'worker_month_dues_paid_year_'.$i;
					$required[] = 'worker_hours_worked_'.$i;
					$required[] = 'worker_hours_paid_'.$i;
				}
			}
			
			// Add Photos
			for($i=1; $i<=$max_photo_uploads; $i++){
				if(!empty($_FILES['photo_'.$i]['name'])){
					$required[] = 'photo_'.$i;
				}
			}
						
			
			
			// Validate form
			$validator = new Validator($required);
			$validator->isInt('type');
			$validator->noFilter('project_name');
			$validator->noFilter('project_location');
			$validator->noFilter('project_city');
			$validator->noFilter('project_county');
			$validator->noFilter('project_start_date');
			$validator->noFilter('pay_period_start');
			$validator->noFilter('pay_period_ending');
			$validator->noFilter('company');
			$validator->noFilter('general_contractor');
			$validator->noFilter('job_duration');
			$validator->isFloat('percent_completed');
			$validator->isInt('contractor_welfare_paid');
			$validator->isInt('num_ironworkers');
			$validator->noFilter('shift');
			$validator->noFilter('journeymen_wages_paid');
			$validator->noFilter('job_phone'); $validator->addAlias('job_phone', 'Contact Phone Number');
			$validator->isInt('job_funding', 2, 4);
			
			$validator->noFilter('job_description');
			
			$validator->noFilter('steward_name');
			$validator->noFilter('steward_address');
			$validator->noFilter('steward_phone');
			$validator->noFilter('steward_email');
			$validator->isInt('steward_email_subscribe');
			if($_POST['contractor_welfare_paid']==0) $validator->isInt('contractor_welfare_paid_to_month');
			
			
			// Edit Foremen
			if($foremen > 0){
				foreach($foremen as $foreman){
					$validator->noFilter('eforeman_first_name_'.$foreman['foreman_id']);
					$validator->noFilter('eforeman_last_name_'.$foreman['foreman_id']);
				}
			}
			
			// Edit Accidents
			if($accidents > 0){
				foreach($accidents as $accident){
					$validator->noFilter('eaccident_description_'.$accident['accident_id']);
					$validator->isInt('eaccident_month_'.$accident['accident_id'], 1, 12);
					$validator->isInt('eaccident_day_'.$accident['accident_id'], 1, 31);
					$validator->isInt('eaccident_year_'.$accident['accident_id']);
				}
			}
			
			// Edit Workers
			if($workers > 0){
				foreach($workers as $worker){
					$validator->noFilter('eworker_first_name_'.$worker['worker_id']);
					$validator->noFilter('eworker_last_name_'.$worker['worker_id']);
					$validator->isInt('eworker_local_number_'.$worker['worker_id']);
					$validator->isInt('eworker_type_'.$worker['worker_id']);
					$validator->isInt('eworker_book_number_'.$worker['worker_id']);
					$validator->isInt('eworker_month_dues_paid_month_'.$worker['worker_id']);
					$validator->isInt('eworker_month_dues_paid_year_'.$worker['worker_id']);
					
					$validator->isFloat('eworker_time_straight_'.$worker['worker_id']);
					$validator->isFloat('eworker_time_half_'.$worker['worker_id']);
					$validator->isFloat('eworker_time_double_'.$worker['worker_id']);
					
					$validator->isFloat('eworker_hours_worked_'.$worker['worker_id']);
					$validator->isFloat('eworker_hours_paid_'.$worker['worker_id']);
				}
			}
			
			
			
			// Add Foremen
			for($i=1; $i<=$num_foremen; $i++){
				if(!empty($_POST['foreman_first_name_'.$i])){
					$validator->noFilter('foreman_first_name_'.$i);
					$validator->noFilter('foreman_last_name_'.$i);
				}
			}
			
			// Add Accidents
			for($i=1; $i<=$num_accidents; $i++){
				if(!empty($_POST['accident_description_'.$i])){
					$validator->noFilter('accident_description_'.$i);
					$validator->isInt('accident_month_'.$i, 1, 12);
					$validator->isInt('accident_day_'.$i, 1, 31);
					$validator->isInt('accident_year_'.$i, 2000, date('Y'));
				}
			}
			
			// Add Workers
			for($i=1; $i<=$num_workers; $i++){
				if(!empty($_POST['worker_first_name_'.$i])){
					$validator->noFilter('worker_first_name_'.$i);
					$validator->noFilter('worker_last_name_'.$i);
					$validator->isInt('worker_local_number_'.$i);
					$validator->isInt('worker_type_'.$i);
					$validator->isInt('worker_book_number_'.$i);
					$validator->isInt('worker_month_dues_paid_month_'.$i);
					$validator->isInt('worker_month_dues_paid_year_'.$i);
					
					$validator->isFloat('worker_time_straight_'.$i);
					$validator->isFloat('worker_time_half_'.$i);
					$validator->isFloat('worker_time_double_'.$i);
					
					$validator->isFloat('worker_hours_worked_'.$i);
					$validator->isFloat('worker_hours_paid_'.$i);
				}
			}
			
			// Photos
			for($i=1; $i<=$max_photo_uploads; $i++){
				if(!empty($_FILES['photo_'.$i]['name'])){
					$validator->isValidImageType('photo_'.$i);
					$validator->noFilter('caption_'.$i);
				}
			}
			
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			$error_fields = $validator->getErrorFields();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				
				// Save report chnages
				$result = editStewardsReportV2($report_id, $clean['type'], $clean['project_location'], $clean['project_city'], $clean['project_county'], $clean['project_name'], 
					$clean['project_start_date'], $clean['pay_period_start'], $clean['pay_period_ending'], $clean['company'], $clean['general_contractor'], 
					$clean['job_duration'], $clean['percent_completed'], $clean['contractor_welfare_paid'], 
					$clean['contractor_welfare_paid_to_month'], $clean['num_ironworkers'], $clean['shift'], $clean['journeymen_wages_paid'], cleanPhone($clean['job_phone']), 
					$clean['job_funding'], $clean['job_description'], $clean['steward_name'], $clean['steward_address'], cleanPhone($clean['steward_phone']), $clean['steward_email'], 
					$clean['steward_email_subscribe']);
				if($result){
					
					$alerts->addAlert('The report was successfully updated.', 'success');
					
					
					// Edit Foremen
					if($foremen > 0){
						foreach($foremen as $foreman){
							$fid = $foreman['foreman_id'];
							
							if(!editStewardsReportForeman($fid, $clean['eforeman_first_name_'.$fid], $clean['eforeman_last_name_'.$fid])){
								$alerts->addAlert('A foreman could not be updated.', 'error');
							}
						}
					}
					
					// Edit Accidents
					if($accidents > 0){
						foreach($accidents as $accident){
							$aid = $accident['accident_id'];
							$date = $clean['eaccident_year_'.$aid].'-'.$clean['eaccident_month_'.$aid].'-'.$clean['eaccident_day_'.$aid];
							
							if(!editStewardsReportAccident($aid, $clean['eaccident_description_'.$aid], $date)){
								$alerts->addAlert('An accident could not be updated.', 'error');
							}
						}
					}
					
					// Edit Workers
					if($workers > 0){
						foreach($workers as $worker){
							$wid = $worker['worker_id'];
							$worker_month_dues_paid = $clean['eworker_month_dues_paid_year_'.$wid].'-'.$clean['eworker_month_dues_paid_month_'.$wid].'-01';
							
							if(!editStewardsReportWorker($wid, $clean['eworker_first_name_'.$wid], $clean['eworker_last_name_'.$wid], $clean['eworker_local_number_'.$wid], $clean['eworker_type_'.$wid], $worker_month_dues_paid, $clean['eworker_hours_paid_'.$wid], $clean['eworker_hours_worked_'.$wid], $clean['eworker_time_straight_'.$wid], $clean['eworker_time_half_'.$wid], $clean['eworker_time_double_'.$wid])){
								$alerts->addAlert('Worker '.$filtered['eworker_first_name_'.$wid].' '.$filtered['eworker_last_name_'.$wid].' could not be updated.', 'error');
							}
						}
					}
					
					
					
					// Add Foremen
					for($i=1; $i<=$num_foremen; $i++){
						if(!empty($_POST['foreman_first_name_'.$i])){
							
							if(newStewardsReportForeman($report_id, $clean['foreman_first_name_'.$i], $clean['foreman_last_name_'.$i]) <= 0){
								$alerts->addAlert('The foreman, "'.$filtered['foreman_first_name_'.$i].' '.$filtered['foreman_last_name_'.$i].'", could not be saved.');
							}
						}
					}
					
					// Add Accidents
					for($i=1; $i<=$num_accidents; $i++){
						if(!empty($_POST['accident_description_'.$i])){
							$date = $clean['accident_year_'.$i].'-'.$clean['accident_month_'.$i].'-'.$clean['accident_day_'.$i];
							
							if(newStewardsReportAccident($report_id, $clean['accident_description_'.$i], $date) <= 0){
								$alerts->addAlert("The accident dated $date could not be saved.");
							} else {
								incrementStewardsReportValue($report_id, 'num_accidents');
							}
						}
					}
					
					// Add Workers
					for($i=1; $i<=$num_workers; $i++){
						if(!empty($_POST['worker_first_name_'.$i])){
							
							$worker_month_dues_paid = $clean['worker_month_dues_paid_year_'.$i].'-'.$clean['worker_month_dues_paid_month_'.$i].'-01';
							
							$book_number = $clean['worker_book_number_'.$i];
							$is_ssn = false;
							
							// Not local 3?
							if($filtered['worker_local_number_'.$i] != LOCAL_NUMBER){
								incrementStewardsReportValue($report_id, 'num_workers_travelers');
							}
							
							if(newStewardsReportWorker($report_id, $clean['worker_first_name_'.$i], $clean['worker_last_name_'.$i], $clean['worker_local_number_'.$i], $clean['worker_type_'.$i], $book_number, $worker_month_dues_paid, $clean['worker_hours_paid_'.$i], $clean['worker_hours_worked_'.$i], $clean['worker_time_straight_'.$i], $clean['worker_time_half_'.$i], $clean['worker_time_double_'.$i], $is_ssn) <= 0){
								$alerts->addAlert("The worker, {$filtered['worker_first_name_{$i}']} {$filtered['worker_last_name_{$i}']}, could not be saved.");
							}
						}
					}
					
					updateStewardsReportTotalHours($report_id);
					
					
					// Add Photos
					for($i=1; $i<=$max_photo_uploads; $i++){
						if(!empty($_FILES['photo_'.$i]['name'])){
							$extension = getExtension($_FILES['photo_'.$i]['name']);
							$filename = uniqid(time()).'.'.$extension;
							
							if(move_uploaded_file($_FILES['photo_'.$i]['tmp_name'], $file_paths['stewards_reports_photos'].$filename)){
								if(newStewardsReportPhoto($report_id, $filename, $clean['caption_'.$i]) <= 0){
									// Photo could not be added.
									unlink($file_paths['stewards_reports_photos'].$filename);
								} else {
									incrementStewardsReportValue($report_id, 'num_photos');
								}
							} else {
								// Photo could not be uploaded.
							}
						}
					}
					
					unset($_POST);
					
				} else {
					$alerts->addAlert('The report could not be saved.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again. '.$e);
		}
	}
	
	
	
	// Delete a photo
	if(!empty($_GET['d1'])){
		$photo_id = (int) $_GET['d1'];
		if(deleteStewardsReportPhoto($photo_id) > 0){
			$alerts->addAlert('The photo was successfully deleted.', 'success');
			decrementStewardsReportValue($report_id, 'num_photos');
		} else {
			$alerts->addAlert('The photo could not be deleted.', 'error');
		}
	}
	
		
	// Delete an accident
	if(!empty($_GET['d2'])){
		$accident_id = (int) $_GET['d2'];
		if(deleteStewardsReportAccident($accident_id) > 0){
			$alerts->addAlert('The accident was successfully deleted.', 'success');
			decrementStewardsReportValue($report_id, 'num_accidents');
		} else {
			$alerts->addAlert('The accident could not be deleted.', 'error');
		}
	}
	
	
	// Delete a worker
	if(!empty($_GET['d3'])){
		$worker_id = (int) $_GET['d3'];
		$worker_info = getStewardsReportWorker($worker_id);
		if(deleteStewardsReportWorker($worker_id) > 0){
			$alerts->addAlert('The worker was successfully deleted.', 'success');
			
			if($worker_info['is_ssn']){
				decrementStewardsReportValue($report_id, 'num_workers_snn');
			}
			
			if($worker_info['local_number'] != LOCAL_NUMBER){
				decrementStewardsReportValue($report_id, 'num_workers_travelers');
			}
		} else {
			$alerts->addAlert('The worker could not be deleted.', 'error');
		}
	}
	
	
	// Delete a foreman
	if(!empty($_GET['d4'])){
		$foreman_id = (int) $_GET['d4'];
		if(deleteStewardsReportForeman($foreman_id) > 0){
			$alerts->addAlert('The foreman was successfully deleted.', 'success');
		} else {
			$alerts->addAlert('The foreman could not be deleted.', 'error');
		}
	}
	
	
	// Delete a worker
	if(!empty($_GET['d5'])){
		$worker_id = (int) $_GET['d5'];
		if(deleteStewardsReportWorker($worker_id) > 0){
			$alerts->addAlert('The worker was successfully deleted.', 'success');
			
			updateStewardsReportTotalHours($report_id);
		} else {
			$alerts->addAlert('The worker could not be deleted.', 'error');
		}
	}
	
	
	
	// Report info
	$report_info = getStewardsReport($report_id);
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<title>Steward's Report | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<link type="text/css" rel="stylesheet" href="/css/stewards_reports.css" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" />

<script type="text/javascript" src="/js/stewards_reports.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.numeric.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>
<script type="text/javascript">
// Define variables used
var num_foremen = <?php echo $num_foremen; ?>;
var num_workers = <?php echo $num_workers; ?>;
var worker_bkgd = '#efefef';
var num_accidents = <?php echo $num_accidents; ?>;
var accident_bkgd = '#fff';
</script>
<link type="text/css" rel="stylesheet" href="/js/fancybox/fancybox.css" media="screen" />
<script type="text/javascript" src="/js/fancybox/fancybox.js"></script>
<script type="text/javascript">
$(function(){
	
	// Photos lightbox gallery
	$('ul.photos a.img').fancybox({titlePosition: 'inside'});
	
});
</script>
<?php include("analytics.php"); ?>
</head>

<body class="edit">
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
         <h1>Edit a Steward&rsquo;s Report</h1>
         
         <p style="margin-bottom:20px;">Use the form below to edit a Steward's Report. <strong>All fields are required.</strong></p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>?rid=<?php echo $report_id; ?>" method="post" enctype="multipart/form-data" id="stewards_report">
         <input type="hidden" name="nwd" id="nwd" value="<?php echo $num_work_descriptions; ?>" />
         <input type="hidden" name="nw" id="nw" value="<?php echo $num_workers; ?>" />
         <input type="hidden" name="na" id="na" value="<?php echo $num_accidents; ?>" />
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
            	<div class="label">Report Type</div>
               <input type="radio" name="type" id="type_regular" value="<?php echo STEWARDS_REPORT_TYPE_REGULAR; ?>"<?php echo ($report_info['type']==STEWARDS_REPORT_TYPE_REGULAR) ? ' checked="checked"' : ''; ?> class="radio" /> 
               <label for="type_regular" class="plain">Regular Steward's Report</label> 
               
               <input type="radio" name="type" id="type_preengineered_metal" value="<?php echo STEWARDS_REPORT_TYPE_PREENGINEERED_METAL; ?>"<?php echo ($report_info['type']==STEWARDS_REPORT_TYPE_PREENGINEERED_METAL) ? ' checked="checked"' : ''; ?> class="radio" /> 
               <label for="type_preengineered_metal" class="plain">Pre-Engineered Metal Building Report</label> 
               
               <input type="radio" name="type" id="type_job_targeted" value="<?php echo STEWARDS_REPORT_TYPE_JOB_TARGETED; ?>"<?php echo ($report_info['type']==STEWARDS_REPORT_TYPE_JOB_TARGETED) ? ' checked="checked"' : ''; ?> class="radio" /> 
               <label for="type_job_targeted" class="plain">Job Targeted Report</label>
            </div>
         </div> <!-- .row -->
         <div class="row">
         		<div class="block full">
               <label for="project_name">Name/Description of Project</label> 
               <input name="project_name" id="project_name" value="<?php echo htmlentities($report_info['project_name'], ENT_QUOTES); ?>" style="width:450px;" />
            </div>
         </div> <!-- .row -->
         <div class="row">
            <div class="block">
               <label for="project_location">Project Address/Location</label> 
               <input name="project_location" id="project_location" value="<?php echo htmlentities($report_info['project_location'], ENT_QUOTES); ?>" />
               	            </div>
            <div class="block">
               <label for="project_city">City</label> 
               <input type="text" name="project_city" id="project_city" value="<?php echo htmlentities($report_info['project_city'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <label for="project_county">County</label> 
               <input name="project_county" id="project_county" value="<?php echo htmlentities($report_info['project_county'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <div class="label">Project Start Date</div> 
               <input type="text" name="project_start_date" id="project_start_date" class="datepicker" value="<?php echo htmlentities($report_info['project_start_date'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Pay Period Start</div> 
               <input type="text" name="pay_period_start" id="pay_period_start" class="datepicker" value="<?php echo htmlentities($report_info['pay_period_start'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Pay Period End</div> 
               <input type="text" name="pay_period_ending" id="pay_period_ending" class="datepicker" value="<?php echo htmlentities($report_info['pay_period_ending'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
         </div><!-- .row -->
         <div class="row">
         	<div class="block half">
               <label for="company">Company - In which you are employed</label> 
               <input name="company" id="company" value="<?php echo htmlentities($report_info['company'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block half">
              <label for="general_contractor">General Contractor</label> 
            	<input name="general_contractor" id="general_contractor" value="<?php echo htmlentities($report_info['general_contractor'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <label for="job_duration">Duration of Job</label> 
               <input type="text" name="job_duration" id="job_duration" value="<?php echo htmlentities($report_info['job_duration'], ENT_QUOTES, 'utf-8'); ?>" style="width:149px;" />
            </div>
            <div class="block">
               <label for="percent_completed">% of Completion</label> 
               <select name="percent_completed" id="percent_completed">
               	<option value=""></option>
<?php
				for($i=10; $i<=100; $i+=10){
?>
               	<option value="<?php echo $i; ?>"<?php echo ($report_info['percent_completed']==$i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?>%</option>
<?php
				}
?>
               </select>
            </div>
            <div class="block">
               <div class="label">Contractors Welfare/Pension Paid Up?</div> 
               <input type="radio" name="contractor_welfare_paid" id="contractor_welfare_paid_yes" value="1"<?php echo ($report_info['contractor_welfare_paid']==1) ? ' checked="checked"' : ''; ?> class="radio" onclick="$('#contractor_welfare_paid_to_month').hide();" /> 
               <label for="contractor_welfare_paid_yes" class="plain">Yes</label> 
               
               <input type="radio" name="contractor_welfare_paid" id="contractor_welfare_paid_no" value="0"<?php echo ($report_info['contractor_welfare_paid']==0) ? ' checked="checked"' : ''; ?> class="radio" onclick="$('#contractor_welfare_paid_to_month').show();" /> 
               <label for="contractor_welfare_paid_no" class="plain">No</label>
               
               <select name="contractor_welfare_paid_to_month" id="contractor_welfare_paid_to_month" style="display:<?php echo ($report_info['contractor_welfare_paid']==0) ? 'inline' : 'none'; ?>;margin-left:5px;">
               	<option value="">[Paid to Month]</option>
<?php
				for($i=1; $i<=12; $i++){
?>
						<option value="<?php echo $i; ?>"<?php echo ($report_info['contractor_welfare_paid_to_month']==$i) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php	
				}
?>
               </select>
            </div>
         </div><!-- .row -->
         <div class="row">
         		<div class="block">
               <label for="num_ironworkers">Total No. of Iron Workers on this Report</label> 
               <input type="text" name="num_ironworkers" id="num_ironworkers" value="<?php echo htmlentities($report_info['num_ironworkers'], ENT_QUOTES, 'utf-8'); ?>" style="width:80px;" class="numeric" />
            </div>
            <div class="block">
               <label for="shift">Shift</label> 
               <select name="shift" id="shift">
               	<option value=""></option>
								<option value="1st"<?php echo ($report_info['shift']=='1st') ? ' selected="selected"' : ''; ?>>1st</option>
								<option value="2nd"<?php echo ($report_info['shift']=='2nd') ? ' selected="selected"' : ''; ?>>2nd</option>
								<option value="3rd"<?php echo ($report_info['shift']=='3rd') ? ' selected="selected"' : ''; ?>>3rd</option>
               </select>
            </div>
            <div class="block">
               <label for="journeymen_wages_paid">Journeymen Wages on this Project Paid at</label> 
               <input name="journeymen_wages_paid" id="journeymen_wages_paid" value="<?php echo htmlentities($report_info['journeymen_wages_paid'], ENT_QUOTES, 'utf-8'); ?>" style="width:80px;" />
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <label for="job_phone">Contact Phone Number</label> 
               <input type="text" name="job_phone" id="job_phone" value="<?php echo formatPhone($report_info['job_phone'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Job Funding</div> 
               <input type="radio" name="job_funding" id="job_funding_state" value="<?php echo JOB_FUNDING_STATE; ?>"<?php echo ($report_info['job_funding']==JOB_FUNDING_STATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_state" class="plain">State</label>
               <input type="radio" name="job_funding" id="job_funding_federal" value="<?php echo JOB_FUNDING_FEDERAL; ?>"<?php echo ($report_info['job_funding']==JOB_FUNDING_FEDERAL) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_federal" class="plain">Federal</label>
               <input type="radio" name="job_funding" id="job_funding_private" value="<?php echo JOB_FUNDING_PRIVATE; ?>"<?php echo ($report_info['job_funding']==JOB_FUNDING_PRIVATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_private" class="plain">Private</label>
            </div>
            <div class="block">
            	
            </div>
         </div><!-- .row -->

				<br style="clear:left;" />
         
         
         <h2>Foremen</h2>
         <div id="foremen">
<?php
		// Get Foremen
		$foremen = getStewardsReportForemen($report_id);
		if($foremen > 0){
?>
					<fieldset>
         		<div class="foreman head">
               <div class="first_name">Foreman First Name</div>
               <div class="last_name">Foreman Last Name</div>
            </div>
<?php
			$i=1;
			foreach($foremen as $foreman){
				
				include('stewards_report_form_foreman_edit.php');
				
				$i++;
			}
?>
					</fieldset>
<?php
		}
?>

					<fieldset id="foremen_appender">
						<legend>Add Foremen</legend>
         		<div class="foreman head">
               <div class="first_name">Foreman First Name</div>
               <div class="last_name">Foreman Last Name</div>
            </div>
<?php


		// Add a foreman
		for($i=1; $i<=$num_accidents; $i++){
			$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';

			include('stewards_report_form_foreman.php');

		}
?>
						<div class="add_foreman">
		            <a href="javascript:addForeman('foremen_appender');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Foreman</a>
		         </div>
					</fieldset>
         </div><!-- div#foremen -->
         
         
         <br style="clear:left;" />
         

         <div class="row">
            <div class="block full">
            	<div id="work_descriptions">
            		<div class="work_description">
									<label for="job_description">Description of Work</label> 
									<textarea name="job_description" id="job_description" cols="50" rows="5"><?php echo $report_info['job_description']; ?></textarea>
								</div><!-- div.work_description -->
            	</div><!-- div#work_descriptions -->
            </div>
         </div><!-- .row -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Accidents <em>For Internal Use Only</em></h2>
         <div id="accidents">
<?php
		// Get accidents
		$accidents = getStewardsReportAccidents($report_id);
		if($accidents > 0){
?>
					<fieldset>
         		<div class="accident head">
               <div class="description">Description of Accident</div>
               <div class="date">Date of Accident</div>
            </div>
<?php
			foreach($accidents as $accident){
				
				include('stewards_report_form_accident_edit.php');
				
			}
?>
					</fieldset>
<?php
		}
?>

					<fieldset id="accidents_appender">
						<legend>Add Accidents</legend>
         		<div class="accident head">
               <div class="description">Description of Accident</div>
               <div class="date">Date of Accident</div>
            </div>
<?php


		// Add an accident
		for($i=1; $i<=$num_accidents; $i++){
			$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';

			include('stewards_report_form_accident.php');

		}
?>
						<div id="add_accident">
		            <a href="javascript:addAccident('accidents_appender');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Accident</a>
		         </div>
					</fieldset>
         </div><!-- div#accidents -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Workers</h2>
         <div id="workers">
         	<fieldset>
         		<div class="worker head">
         			<div class="num">&nbsp;</div>
            	<div class="book_number">Book No.</div>
               <div class="first_name">First Name</div>
               <div class="last_name">Last Name</div>
               <div class="local_number">Local No.</div>
               <div class="type">Type</div>
               <div class="month_dues_paid">Month Paid</div>
            </div>
<?php
		$total_hours = 0;
		
		// Get existing workers
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
				list($year_dues_paid, $month_dues_paid, $day_dues_paid) = explode('-', $worker['month_dues_paid']);

				include('stewards_report_form_worker_edit.php');

				$i++;
			}
		}
?>
					</fieldset>
					<fieldset id="workers_appender">
						<legend>Add Workers</legend>
						<div class="worker head">
         			<div class="num">&nbsp;</div>
            	<div class="book_number">Book No.</div>
               <div class="first_name">First Name</div>
               <div class="last_name">Last Name</div>
               <div class="local_number">Local No.</div>
               <div class="type">Type</div>
               <div class="month_dues_paid">Month Paid</div>
            </div>
<?php
		
		
		// Add workers
		for($i=1; $i<=$num_workers; $i++){
			$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
			
			include('stewards_report_form_worker_v2.php');
		}
?>
						<div class="add_worker">
               <a href="javascript:addWorker('workers_appender');"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Worker</a>
            </div>
					</fieldset>
         </div><!-- div#workers -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Job Site Photos</h2>
         
<?php
		$photos = getStewardsReportPhotos($report_id);
		if($photos > 0){
?>        
				 <fieldset>
	         	<ul class="photos">
<?php
			foreach($photos as $photo){
?>
							<li>
								<a href="<?php echo new Image($photo['filename'], 1000, 900, 'fit', 'stewards_reports_photos'); ?>" title="<?php echo htmlentities($photo['caption'], ENT_QUOTES); ?>" rel="photos" class="img"><img src="<?php echo new Image($photo['filename'], 400, 100, 'fit', 'stewards_reports_photos'); ?>" alt="" /></a><br />
								<a href="javascript:confirm_deletion(1, <?php echo $photo['photo_id']; ?>, '<?php echo getSelf(); ?>?rid=<?php echo $report_id; ?>', 'photo', 1);">Delete</a>
							</li>
<?php
			}
?>
	         	</ul>
	      </fieldset>
<?php
		}
?>
         
         <fieldset>
				 	 <legend>Add Photos</legend>
         		<div class="row">
<?php
			for($i=0; $i<$max_photo_uploads; $i++){
				if($i>0 && $i%2==0) echo '</div><div class="row">';
?>
         			<div class="block half">
         				<input type="file" name="photo_<?php echo $i+1; ?>" id="photo_<?php echo $i+1; ?>" size="8" style="width: 175px;" /> 
         				<input type="text" name="caption_<?php echo $i+1; ?>" id="caption_<?php echo $i+1; ?>" value="<?php echo htmlentities($_POST['caption_'.$i+1], ENT_QUOTES); ?>" placeholder="Caption" style="width: 160px;" />
         			</div>
<?php
			}
?>
        	 </div><!-- .row -->
       	 </fieldset>
         
         
         <br style="clear:left;" />
                 
         
         <div class="row">
         	<div class="block quarter">
               <label for="steward_name">Steward's Name</label> 
               <input type="text" name="steward_name" id="steward_name" value="<?php echo htmlentities($report_info['steward_name'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_address">Address</label> 
               <input type="text" name="steward_address" id="steward_address" value="<?php echo htmlentities($report_info['steward_address'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_phone">Phone</label> 
               <input type="text" name="steward_phone" id="steward_phone" value="<?php echo htmlentities($report_info['steward_phone'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_phone">Email</label> 
               <input type="text" name="steward_email" id="steward_email" value="<?php echo htmlentities($report_info['steward_email'], ENT_QUOTES, 'utf-8'); ?>" /><br>
               
               <input type="checkbox" name="steward_email_subscribe" id="steward_email_subscribe" class="checkbox" value="1"<?php echo ($report_info['steward_email_subscribe'] == 1) ? ' checked="checked"' : ''; ?> /> 
               <label for="steward_email_subscribe" class="normal">Join Email List</label>
            </div>
         </div><!-- .row -->
         
         <div class="row submit">
         	<div class="block full">
         		<input type="submit" name="submit" id="submit" value="Update Report" />
            </div>
         </div><!-- .row -->
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>