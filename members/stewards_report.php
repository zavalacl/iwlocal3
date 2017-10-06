<?php
    require('config.php');
    $access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
    require('functions/stewards_reports.php');
    require('functions/contractors.php');
    
    $num_foremen           = (! empty($_POST['nf'])) ? (int) $_POST['nf'] : 2; // Number of foremen rows
    $num_accidents         = (! empty($_POST['na'])) ? (int) $_POST['na'] : 2; // Number of accident rows
    $num_workers           = (! empty($_POST['nw'])) ? (int) $_POST['nw'] : 5; // Number of worker rows
    $num_work_descriptions = (! empty($_POST['nwd'])) ? (int) $_POST['nwd'] : 1; // Number of work description rows
    $max_photo_uploads     = 6; // Max number of images that can be uploaded
    
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';
    
    // Submit Form
    if(isset($_POST['submit'])){
        try {
            
            // Required fields
            $required = array('type', 'project_city', 'project_county', 'project_name', 'project_start_date', 'pay_period_start', 'pay_period_ending', 'company', 'general_contractor', 'job_duration', 'percent_completed', 'num_ironworkers', 'job_funding', 'steward_name', 'steward_address', 'steward_phone');
            
            if(! empty($_POST['project_location']))    $required[] = 'project_location'; else $required[] = 'project_address';
            if($_POST['contractor_welfare_paid'] == 0) $required[] = 'contractor_welfare_paid_to_month';
            
            // Foremen
            for($i=1; $i<=$num_foremen; $i++){
                if(! empty($_POST['foreman_first_name_'.$i])){
                    $required[] = 'foreman_first_name_'.$i;
                    $required[] = 'foreman_last_name_'.$i;
                }
            }
            
            // Work/Job Descriptions
            for($i=1; $i<=$num_work_descriptions; $i++){
                if(! empty($_POST['work_description_category_'.$i])){
                    $required[] = 'work_description_category_'.$i;
                }
            }
            
            // Accidents
            for($i=1; $i<=$num_accidents; $i++){
                if(! empty($_POST['accident_description_'.$i])){
                    $required[] = 'accident_description_'.$i;
                    $required[] = 'accident_month_'.$i;
                    $required[] = 'accident_day_'.$i;
                    $required[] = 'accident_year_'.$i;
                }
            }
            
            // Workers
            for($i=1; $i<=$num_workers; $i++){
                if(! empty($_POST['worker_first_name_'.$i])){
                    $required[] = 'worker_first_name_'.$i;
                    $required[] = 'worker_last_name_'.$i;
                    $required[] = 'worker_local_number_'.$i;
                    $required[] = 'worker_type_'.$i;
                    $required[] = 'worker_book_number_'.$i;
                    $required[] = 'worker_month_dues_paid_month_'.$i;
                    $required[] = 'worker_month_dues_paid_year_'.$i;
                }
            }
            
            // Photos
            for($i=1; $i<=$max_photo_uploads; $i++){
                if(! empty($_FILES['photo_'.$i]['name'])){
                    $required[] = 'photo_'.$i;
                }
            }
                        
            
            
            // Validate form
            $validator = new Validator($required);
            $validator->isInt('type');
            $validator->noFilter('project_name');
            $validator->noFilter('project_name_other');
            $validator->noFilter('project_location');
            $validator->noFilter('project_location_other');
            $validator->noFilter('project_address');
            $validator->noFilter('project_city');
            $validator->noFilter('project_county');
            $validator->noFilter('project_county_other');
            $validator->isValidDate('project_start_date');
            $validator->isValidDate('pay_period_start');
            $validator->isValidDate('pay_period_ending');
            $validator->noFilter('company');
            $validator->noFilter('company_other');
            $validator->noFilter('general_contractor');
            $validator->noFilter('general_contractor_other');
            $validator->noFilter('job_duration');
            $validator->isFloat('percent_completed');
            $validator->isInt('contractor_welfare_paid');
            $validator->isInt('num_ironworkers');
            $validator->noFilter('shift');
            $validator->noFilter('journeymen_wages_paid');
            $validator->noFilter('journeymen_wages_paid_other');
            $validator->noFilter('job_phone'); $validator->addAlias('job_phone', 'Contact Phone Number');
            $validator->isInt('job_funding', 2, 4);
            $validator->noFilter('steward_name');
            $validator->noFilter('steward_address');
            $validator->noFilter('steward_phone');
            $validator->noFilter('steward_email');
            $validator->isInt('steward_email_subscribe');
            if($_POST['contractor_welfare_paid']==0) $validator->isInt('contractor_welfare_paid_to_month');
            
            
            // Foremen
            for($i=1; $i<=$num_foremen; $i++){
                if(! empty($_POST['foreman_first_name_'.$i])){
                    $validator->noFilter('foreman_first_name_'.$i);
                    $validator->noFilter('foreman_last_name_'.$i);
                }
            }
            
            // Work/Job Descriptions
            for($i=1; $i<=$num_work_descriptions; $i++){
                if(! empty($_POST['work_description_category_'.$i])){
                    $validator->isInt('work_description_category_'.$i);
                    $validator->isInt('wdv_'.$i);
                    $validator->noFilter('wdv_other_'.$i);
                    $validator->noFilter('wdv_describe_'.$i);
                }
            }
            
            // Accidents
            for($i=1; $i<=$num_accidents; $i++){
                if(! empty($_POST['accident_description_'.$i])){
                    $validator->noFilter('accident_description_'.$i);
                    $validator->isInt('accident_month_'.$i, 1, 12);
                    $validator->isInt('accident_day_'.$i, 1, 31);
                    $validator->isInt('accident_year_'.$i, 2000, date('Y'));
                }
            }
            
            // Workers
            $actual_num_workers = 0;
            for($i=1; $i<=$num_workers; $i++){
                if(! empty($_POST['worker_first_name_'.$i])){
                    $actual_num_workers++;
                    
                    $validator->noFilter('worker_first_name_'.$i);
                    $validator->noFilter('worker_last_name_'.$i);
                    $validator->isInt('worker_local_number_'.$i);
                    $validator->isInt('worker_type_'.$i);
                    $validator->noFilter('worker_book_number_'.$i);
                    $validator->isInt('worker_month_dues_paid_month_'.$i);
                    $validator->isInt('worker_month_dues_paid_year_'.$i);
                    
                    $validator->isFloat('worker_time_straight_'.$i);
                    $validator->isFloat('worker_time_half_'.$i);
                    $validator->isFloat('worker_time_double_'.$i);
                }
            }
            
            // Photos
            for($i=1; $i<=$max_photo_uploads; $i++){
                if(! empty($_FILES['photo_'.$i]['name'])){
                    $validator->isValidImageType('photo_'.$i);
                    $validator->noFilter('caption_'.$i);
                }
            }
            
            
            $filtered = $validator->validateInput();
            $errors = $validator->getErrors();
            $error_fields = $validator->getErrorFields();
            
            
            // Make sure that the number entered in "Total No. of Iron Workers on this Report"
            // matches the actual number of members entered
            if((int) $_POST['num_ironworkers'] !== (int) $actual_num_workers){
                $errors[] = 'The field, "Total No. of Iron Workers on this Report", does not match the number of workers entered.';
            }
            
            
            if(! $errors){
                $clean = array_map('escapeData', $filtered);
                
                // Project name
                if($clean['project_name']!='Other'){
                    $project_id = (int) $clean['project_name'];
                    $project_info = getProject($project_id);
                    $project_name = escapeData($project_info['name']);
                } else {
                    if(! empty($clean['project_name_other'])){
                        $project_name = $clean['project_name_other'];
                    } else {
                        $project_name = 'Other';
                    }
                }
                                                
                // Project location
                if(! empty($clean['project_address'])){
                    $project_location = $clean['project_address'];
                
                } else if($clean['project_location']!='Other'){
                    $location_id = (int) $clean['project_location'];
                    $location_info = getProjectLocation($location_id);
                    $project_location = escapeData($location_info['address']);
                
                } else {
                    if(! empty($clean['project_location_other'])){
                        $project_location = $clean['project_location_other'];
                    } else {
                        $project_location = 'Other';
                    }
                }
                
                // Company/employer
                if($clean['company'] != 'Other'){
                    $employer_id = $company_id = (int) $clean['company'];
                    $employer_info = getEmployer($employer_id);
                    $company = escapeData($employer_info['name']);
                } else {
                    $employer_id = $company_id = null;
                    
                    if(! empty($clean['company_other'])){
                        $company = $clean['company_other'];
                    } else {
                        $company = 'Other';
                    }
                }
                
                $project_county = ($clean['project_county']!='Other') ? $clean['project_county'] : ((! empty($clean['project_county_other'])) ? $clean['project_county_other'] : 'Other');
                $general_contractor = ($clean['general_contractor']!='Other') ? $clean['general_contractor'] : ((! empty($clean['general_contractor_other'])) ? $clean['general_contractor_other'] : 'Other');
                $journeymen_wages_paid = ($clean['journeymen_wages_paid']!='Other') ? $clean['journeymen_wages_paid'] : ((! empty($clean['journeymen_wages_paid_other'])) ? $clean['journeymen_wages_paid_other'] : 'Other');
                
                
                // Save report
                $report_id = newStewardsReportV2(getUserId(), $clean['type'], $project_location, $clean['project_city'], $project_county, $project_name, 
                    $clean['project_start_date'], $clean['pay_period_start'], $clean['pay_period_ending'], $company_id, $company, $general_contractor, 
                    $clean['job_duration'], $clean['percent_completed'], $clean['contractor_welfare_paid'], 
                    $clean['contractor_welfare_paid_to_month'], $clean['num_ironworkers'], $clean['shift'], $journeymen_wages_paid, cleanPhone($clean['job_phone']), 
                    $clean['job_funding'], '', $clean['steward_name'], 
                    $clean['steward_address'], cleanPhone($clean['steward_phone']), $clean['steward_email'], $clean['steward_email_subscribe']);
                if($report_id > 0){
                    
                    $alerts->addAlert('The report was successfully saved. <a href="/download.php?id='.$report_id.'&amp;t=stewards_reports">Click here</a> to download a PDF copy of the report.', 'success');
                    
                    $alerts->addAlert('If you made a mistake while completing the report, please <a href="stewards_reports_contact.php?rid='.$report_id.'" target="_blank">click here</a> to let us know.', 'success');
                    
                    
                    // Foremen
                    for($i=1; $i<=$num_foremen; $i++){
                        if(! empty($_POST['foreman_first_name_'.$i])){                            
                            if(newStewardsReportForeman($report_id, $clean['foreman_first_name_'.$i], $clean['foreman_last_name_'.$i]) <= 0){
                                $alerts->addAlert('The foreman, "'.$filtered['foreman_first_name_'.$i].' '.$filtered['foreman_last_name_'.$i].'", could not be saved.');
                            }
                        }
                    }
                    
                    // Work/Job Descriptions
                    $descriptions = "";
                    for($i=1; $i<=$num_work_descriptions; $i++){
                        if(! empty($clean['work_description_category_'.$i])){
                            $item_id = (! empty($clean['wdv_'.$i])) ? $clean['wdv_'.$i] : false;
                            $other = (! empty($clean['wdv_other_'.$i]) && $clean['wdv_other_'.$i] != 'Type info here') ? stripslashes($clean['wdv_other_'.$i]) : '';
                            $describe = (! empty($clean['wdv_describe_'.$i]) && $clean['wdv_describe_'.$i] != 'Describe') ? stripslashes($clean['wdv_describe_'.$i]) : '';
                            
                            $descriptions .= getWorkDescriptionTreeAsString($item_id, $clean['work_description_category_'.$i], $other, $describe)."\n";
                        }
                    }
                    if(! empty($descriptions)){
                        $descriptions = escapeData($descriptions);
                        if(setStewardsReportJobDescriptions($report_id, $descriptions) <= 0){
                            $alerts->addAlert("The job descriptions could not be saved.");
                        }
                    }
                    
                    // Accidents
                    $real_num_accidents = 0;
                    for($i=1; $i<=$num_accidents; $i++){
                        if(! empty($_POST['accident_description_'.$i])){
                            $real_num_accidents++;
                            $date = $clean['accident_year_'.$i].'-'.$clean['accident_month_'.$i].'-'.$clean['accident_day_'.$i];
                            
                            if(newStewardsReportAccident($report_id, $clean['accident_description_'.$i], $date) <= 0){
                                $alerts->addAlert("The accident dated $date could not be saved.");
                            }
                        }
                    }
                    
                    // Workers
                    $real_num_workers_ssn = 0;
                    $real_num_workers_travelers = 0;
                    for($i=1; $i<=$num_workers; $i++){
                        if(! empty($_POST['worker_first_name_'.$i])){
                            
                            $worker_month_dues_paid = $clean['worker_month_dues_paid_year_'.$i].'-'.$clean['worker_month_dues_paid_month_'.$i].'-01';
                            
                            $book_number = $clean['worker_book_number_'.$i];
                            $is_ssn = false;
                            
                            // Not from local 3?
                            if($filtered['worker_local_number_'.$i] != LOCAL_NUMBER){
                                $real_num_workers_travelers++;
                            }
                            
                            // Calculate time worked and paid
                            // worked = ST + TH + DT
                            // paid   = ST + (TH * 1.5) + (DT * 2)
                            $time_worked =  $filtered['worker_time_straight_'.$i] + $filtered['worker_time_half_'.$i] + $filtered['worker_time_double_'.$i];
                            $time_paid =  $filtered['worker_time_straight_'.$i] + ($filtered['worker_time_half_'.$i] * 1.5) + ($filtered['worker_time_double_'.$i] * 2);
                            
                            if(newStewardsReportWorker($report_id, $clean['worker_first_name_'.$i], $clean['worker_last_name_'.$i], $clean['worker_local_number_'.$i], $clean['worker_type_'.$i], $book_number, $worker_month_dues_paid, $time_paid, $time_worked, $clean['worker_time_straight_'.$i], $clean['worker_time_half_'.$i], $clean['worker_time_double_'.$i], $is_ssn) <= 0){
                                $alerts->addAlert("The worker, {$filtered['worker_first_name_{$i}']} {$filtered['worker_last_name_{$i}']}, could not be saved.");
                            }
                        }
                    }
                    
                    updateStewardsReportTotalHours($report_id);
                    
                    
                    // Photos
                    $real_num_photos = 0;
                    for($i=1; $i<=$max_photo_uploads; $i++){
                        if(! empty($_FILES['photo_'.$i]['name'])){
                            $extension = getExtension($_FILES['photo_'.$i]['name']);
                            $filename = uniqid(time()).'.'.$extension;
                            
                            if(move_uploaded_file($_FILES['photo_'.$i]['tmp_name'], $file_paths['stewards_reports_photos'].$filename)){
                                if(newStewardsReportPhoto($report_id, $filename, $clean['caption_'.$i]) <= 0){
                                    // Photo could not be added.
                                    unlink($file_paths['stewards_reports_photos'].$filename);
                                } else {
                                    $real_num_photos++;
                                }
                            } else {
                                // Photo could not be uploaded.
                            }
                        }
                    }
                    
                    
                    // Stats
                    if($real_num_accidents > 0){
                        setStewardsReportValue($report_id, 'num_accidents', $real_num_accidents);
                    }
                    if($real_num_photos > 0){
                        setStewardsReportValue($report_id, 'num_photos', $real_num_photos);
                    }
                    if($real_num_workers_ssn > 0){
                        setStewardsReportValue($report_id, 'num_workers_ssn', $real_num_workers_ssn);
                    }
                    if($real_num_workers_travelers > 0){
                        setStewardsReportValue($report_id, 'num_workers_travelers', $real_num_workers_travelers);
                    }
                    
                    
                    // Send email to IW to notify of Steward email list sign-up
                    if($filtered['steward_email_subscribe'] && $filtered['steward_email']){
                        require_once('classes/PHPMailer.php');
                        
                        try {
                            $mail = new PHPMailer();
            
                            $mail->SetFrom('info@iwlocal3.com');
                            //$mail->AddAddress('taylor@taylorcollinsdesign.com');
                            $mail->AddAddress('info@iwlocal3.com');
                            $mail->Subject = "Steward Email List Sign-up";
                            
                            $html = '<b>Steward Email List Sign-up</b><br>
<br>
A steward has signed-up for the email list:<br>
<br>
<b>Name:</b> '.$clean['steward_name'].'<br>
<b>Email:</b> <a href="mailto:'.$clean['steward_email'].'">'.$clean['steward_email'].'</a>';
                            
                            $mail->MsgHTML($html);
                            $mail->Send();
                            
                        } catch (phpmailerException $e) {
                          //$alerts->addAlert($e->errorMessage());
                        } catch (Exception $e) {
                          //$alerts->addAlert($e->getMessage());
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
            $alerts->addAlert('There was an unknown error. Please try again.');
        }
    }
    
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
<link type="text/css" rel="stylesheet" href="/css/stewards_reports.css?t=<?php echo time(); ?>" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.numeric.js"></script>
<script type="text/javascript" src="/js/jquery.validate.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="/js/jquery.autogrow.js"></script>
<script type="text/javascript" src="/js/stewards_reports.js?t=<?php echo time(); ?>"></script>
<script type="text/javascript">
// Define variables used
var num_foremen = <?php echo $num_foremen; ?>;
var num_workers = <?php echo $num_workers; ?>;
var worker_bkgd = '#efefef';
var num_accidents = <?php echo $num_accidents; ?>;
var accident_bkgd = '#fff';
var num_work_descriptions = <?php echo $num_work_descriptions; ?>;
</script>

<!--[if lt IE 9]>
<script type="text/javascript">
$(function(){
    var el;

    $("#project_location")
  .each(function() {
    el = $(this);
    el.data("origWidth", el.outerWidth()) // IE 8 can haz padding
  })
  .mouseenter(function(){
    $(this).css("width", "auto");
  })
  .bind("blur change", function(){
    el = $(this);
    el.css("width", el.data("origWidth"));
  });
  
});
</script>
<![endif]-->
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
    <?php include("header.php"); ?>
   <div id="main" class="inner">
       <div class="left">
          <?php $page='stewards_report'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
         <h1>Steward&rsquo;s Report</h1>
         
         <p class="alerts"><strong>Attention Stewards:</strong> We are currently updating the online stewards report. Below you will see the new report in which we had added some additional fields and programming for your convenience. We are currently testing all new features and you may experience some errors. If you are having problems please call the union hall at (412) 227-6767. Thank you for your patience.</p>
         
         <p style="margin-bottom:20px;">A Steward’s Report is the main form of communication between the Steward on a job site and the Union. As a Steward, we ask that you fill out and submit a report every week. Please complete and submit the form below. <strong>All fields are required.</strong></p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data" id="stewards_report" onsubmit="return validateFormCustom();" autocomplete="off">
         <input type="hidden" name="nf" id="nf" value="<?php echo $num_foremen; ?>" />
         <input type="hidden" name="nwd" id="nwd" value="<?php echo $num_work_descriptions; ?>" />
         <input type="hidden" name="nw" id="nw" value="<?php echo $num_workers; ?>" />
         <input type="hidden" name="na" id="na" value="<?php echo $num_accidents; ?>" />
         
<?php
    $previous_reports = getOwnStewardsReports(getUserId(), 'LIMIT 10');
    if($previous_reports > 0){
?>
            <div class="row">
                <div class="block full">
                    <label for="f-prepopulate-report">Pre-Populate Report <i>Use a recent report to pre-populate this new report.</i></label>
                    <select id="f-prepopulate-report">
                        <option value=""></option>
<?php
                    foreach($previous_reports as $previous_report){
?>
                        <option value="<?php echo $previous_report['report_id']; ?>"><?php echo htmlentities($previous_report['project_name']); ?> - <?php echo date('Y-m-d', strtotime($previous_report['pay_period_ending'])); ?></option>
<?php
                    }
?>
                    </select>
                </div> <!-- .block -->
             </div> <!-- .row -->
<?php
    }
?>
         
         <div class="row">
            <div class="block full">
                <div class="label">Report Type</div>
               <input type="radio" name="type" id="type_regular" value="<?php echo STEWARDS_REPORT_TYPE_REGULAR; ?>"<?php echo ($_POST['type']==STEWARDS_REPORT_TYPE_REGULAR) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="type_regular" class="plain">Regular Steward's Report</label> 
               <input type="radio" name="type" id="type_preengineered_metal" value="<?php echo STEWARDS_REPORT_TYPE_PREENGINEERED_METAL; ?>"<?php echo ($_POST['type']==STEWARDS_REPORT_TYPE_PREENGINEERED_METAL) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="type_preengineered_metal" class="plain">Pre-Engineered Metal Building Report</label> 
               <input type="radio" name="type" id="type_job_targeted" value="<?php echo STEWARDS_REPORT_TYPE_JOB_TARGETED; ?>"<?php echo ($_POST['type']==STEWARDS_REPORT_TYPE_JOB_TARGETED) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="type_job_targeted" class="plain">Job Targeted Report</label>
            </div>
         </div> <!-- .row -->
         
         <div class="row">
                 <div class="block full">
               <label for="project_name">Name/Description of Project</label> 
               <select name="project_name" id="project_name" style="width:450px; margin-right:10px; float:left;" onchange="if(this.value=='Other') $('#project_name_other').show(); else $('#project_name_other').hide();">
                       <option value=""></option>
<?php
                $projects = getProjects();
                if($projects > 0){
                    foreach($projects as $project){
?>
                                    <option value="<?php echo $project['project_id']; ?>"<?php echo ($_POST['project_name'] == $project['project_id']) ? ' selected="selected"' : ''; ?>><?php echo $project['name']; ?></option>
<?php
                    }
                }
?>

                  <option value="Other"<?php echo ($_POST['project_name']=='Other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <textarea name="project_name_other" id="project_name_other" placeholder="Other" style="width:295px; display:<?php echo ($_POST['project_name']=='Other') ? 'block' : 'none'; ?>;"><?php echo htmlentities($_POST['project_name_other'], ENT_QUOTES, 'utf-8'); ?></textarea>
            </div>
         </div> <!-- .row -->
         <div class="row">
            <div class="block">
               <label for="project_location">Project Address/Location</label> 
               <select name="project_location" id="project_location" style="width: 100px; margin-right:10px; float:left;" onchange="if(this.value=='Other') $('#project_location_other').show(); else $('#project_location_other').hide();"<?php echo (! empty($_POST['project_address'])) ? ' class="hide"' : ''; ?>>
                   <option value=""></option>
<?php
                $locations = getProjectLocations();
                if($locations > 0){
                    foreach($locations as $location){
?>
                                    <option value="<?php echo $location['location_id']; ?>"<?php echo ($_POST['project_location'] == $location['location_id']) ? ' selected="selected"' : ''; ?>><?php echo $location['address']; ?></option>
<?php
                    }
                }
?>

                  <option value="Other"<?php echo ($_POST['project_location']=='Other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <textarea name="project_location_other" id="project_location_other" placeholder="Other" style="width:100px; display:<?php echo ($_POST['project_location']=='Other') ? 'block' : 'none'; ?>;"><?php echo htmlentities($_POST['project_location_other'], ENT_QUOTES, 'utf-8'); ?></textarea>
               <input type="text" name="project_address" id="project_address" value="<?php echo htmlentities($_POST['project_address'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <label for="project_city">City</label> 
               <input type="text" name="project_city" id="project_city" value="<?php echo htmlentities($_POST['project_city'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <label for="project_county">County</label> 
               <input type="hidden" name="project_county" id="project_county_hidden" value="<?php echo htmlentities($_POST['project_county'], ENT_QUOTES, 'utf-8'); ?>" />
               <select id="project_county" style="width:100px; margin-right:10px; float:left;" onchange="$('#project_county_hidden').val( this.value ); if(this.value=='Other'){ $('#project_county_other').show(); } else { $('#project_county_other').hide(); }">
                       <option value=""></option>
<?php
                $counties = getProjectCounties();
                if($counties > 0){
                    foreach($counties as $county){
?>
                                    <option value="<?php echo htmlentities($county['county'], ENT_QUOTES, 'utf-8'); ?>"<?php echo ($_POST['project_county'] == $county['county']) ? ' selected="selected"' : ''; ?>><?php echo $county['county']; ?></option>
<?php
                    }
                }
?>

                  <option value="Other"<?php echo ($_POST['project_county']=='Other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <textarea name="project_county_other" id="project_county_other" placeholder="Other" style="width:100px; display:<?php echo ($_POST['project_county']=='Other') ? 'block' : 'none'; ?>;"><?php echo htmlentities($_POST['project_county_other'], ENT_QUOTES, 'utf-8'); ?></textarea>
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <div class="label">Project Start Date <em>YYYY-MM-DD</em></div> 
               <input type="text" name="project_start_date" id="project_start_date" class="datepicker" value="<?php echo htmlentities($_POST['project_start_date'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Pay Period Start <em>YYYY-MM-DD</em></div> 
               <input type="text" name="pay_period_start" id="pay_period_start" class="datepicker" value="<?php echo htmlentities($_POST['pay_period_start'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Pay Period End <em>YYYY-MM-DD</em></div> 
               <input type="text" name="pay_period_ending" id="pay_period_ending" class="datepicker" value="<?php echo htmlentities($_POST['pay_period_ending'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
         </div><!-- .row -->
         <div class="row">
             <div class="block half">
               <label for="company">Company - In which you are employed</label> 
               <select name="company" id="company" style="width:200px; margin-right:10px; float:left;" onchange="if(this.value=='Other') $('#company_other').show(); else $('#company_other').hide();">
                   <option value=""></option>
<?php
                $employers = getEmployers();
                if($employers > 0){
                    foreach($employers as $employer){
?>
                                <option value="<?php echo $employer['employer_id']; ?>"<?php echo ($_POST['company']==$employer['employer_id']) ? ' selected="selected"' : ''; ?>><?php echo htmlentities($employer['name'], ENT_QUOTES, 'utf-8'); ?></option>
<?php        
                    }
                }
?>
                                <option value="Other"<?php echo ($_POST['company']=='Other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <textarea name="company_other" id="company_other" placeholder="Other" style="width:150px; display:<?php echo ($_POST['company']=='Other') ? 'block' : 'none'; ?>;"><?php echo htmlentities($_POST['company_other'], ENT_QUOTES, 'utf-8'); ?></textarea>
            </div>
            <div class="block half">
              <label for="general_contractor">General Contractor</label> 
                <select name="general_contractor" id="general_contractor" style="width:200px; margin-right:10px; float:left;" onchange="if(this.value=='Other') $('#general_contractor_other').show(); else $('#general_contractor_other').hide();">
                   <option value=""></option>
<?php
                $contractors = getGeneralContractors();
                if($contractors > 0){
                    foreach($contractors as $contractor){
?>
                        <option value="<?php echo htmlentities($contractor['name'], ENT_QUOTES, 'utf-8'); ?>"<?php echo ($_POST['general_contractor']==$contractor['name']) ? ' selected="selected"' : ''; ?>><?php echo htmlentities($contractor['name'], ENT_QUOTES, 'utf-8'); ?></option>
<?php        
                    }
                }
?>
                        <option value="Other"<?php echo ($_POST['general_contractor']=='Other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <textarea name="general_contractor_other" id="general_contractor_other" placeholder="Other" style="width:150px; display:<?php echo ($_POST['general_contractor']=='Other') ? 'block' : 'none'; ?>;"><?php echo htmlentities($_POST['general_contractor_other'], ENT_QUOTES, 'utf-8'); ?></textarea>
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <label for="job_duration">Duration of Job</label> 
               <input type="text" name="job_duration" id="job_duration" value="<?php echo htmlentities($_POST['job_duration'], ENT_QUOTES, 'utf-8'); ?>" style="width:149px;" />
            </div>
            <div class="block">
               <label for="percent_completed">% of Completion</label> 
               <select name="percent_completed" id="percent_completed">
                   <option value=""></option>
<?php
                for($i=10; $i<=100; $i+=10){
?>
                   <option value="<?php echo $i; ?>"<?php echo ($_POST['percent_completed']==$i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?>%</option>
<?php
                }
?>
               </select>
            </div>
            <div class="block">
               <div class="label">Contractors Welfare/Pension Paid Up?</div> 
               <input type="radio" name="contractor_welfare_paid" id="contractor_welfare_paid_yes" value="1"<?php echo ($_POST['contractor_welfare_paid']==1) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" onclick="$('#contractor_welfare_paid_to_month').hide();" /> <label for="contractor_welfare_paid_yes" class="plain">Yes</label> 
               <input type="radio" name="contractor_welfare_paid" id="contractor_welfare_paid_no" value="0"<?php echo (isset($_POST['submit']) && $_POST['contractor_welfare_paid']==0) ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" onclick="$('#contractor_welfare_paid_to_month').show();" /> <label for="contractor_welfare_paid_no" class="plain">No</label>
               <select name="contractor_welfare_paid_to_month" id="contractor_welfare_paid_to_month" style="display:<?php echo (isset($_POST['submit']) && $_POST['contractor_welfare_paid']==0) ? 'inline' : 'none'; ?>;margin-left:5px;">
                   <option value="">[Paid to Month]</option>
<?php
                for($i=1; $i<=12; $i++){
?>
                        <option value="<?php echo $i; ?>"<?php echo ($_POST['contractor_welfare_paid_to_month']==$i) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php    
                }
?>
               </select>
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <label for="num_ironworkers">Total No. of Iron Workers on this Report</label> 
               <input type="text" name="num_ironworkers" id="num_ironworkers" value="<?php echo htmlentities($_POST['num_ironworkers'], ENT_QUOTES, 'utf-8'); ?>" style="width:80px;" class="numeric" />
            </div>
            <div class="block">
               <label for="shift">Shift</label> 
               <select name="shift" id="shift">
                    <option value=""></option>
                    <option value="1st"<?php echo ($_POST['shift']=='1st') ? ' selected="selected"' : ''; ?>>1st</option>
                    <option value="2nd"<?php echo ($_POST['shift']=='2nd') ? ' selected="selected"' : ''; ?>>2nd</option>
                    <option value="3rd"<?php echo ($_POST['shift']=='3rd') ? ' selected="selected"' : ''; ?>>3rd</option>
               </select>
            </div>
            <div class="block">
               <label for="journeymen_wages_paid">Journeymen Wages on this Project Paid at</label> 
               <select name="journeymen_wages_paid" id="journeymen_wages_paid" onchange="if(this.value=='Other') $('#journeymen_wages_paid_other').show(); else $('#journeymen_wages_paid_other').hide();">
                   <option value=""></option>
                   <option value="100%"<?php echo ($_POST['journeymen_wages_paid']=='100%') ? ' selected="selected"' : ''; ?>>100%</option>
                   <option value="95%"<?php echo ($_POST['journeymen_wages_paid']=='95%') ? ' selected="selected"' : ''; ?>>95%</option>
                   <option value="90%"<?php echo ($_POST['journeymen_wages_paid']=='90%') ? ' selected="selected"' : ''; ?>>90%</option>
                   <option value="Other"<?php echo ($_POST['journeymen_wages_paid']=='other') ? ' selected="selected"' : ''; ?>>Other</option>
               </select>
               <input type="text" name="journeymen_wages_paid_other" id="journeymen_wages_paid_other" value="<?php echo htmlentities($_POST['journeymen_wages_paid_other'], ENT_QUOTES, 'utf-8'); ?>" placeholder="Other" style="width:60px; display:<?php echo ($_POST['journeymen_wages_paid']=='Other') ? 'block' : 'none'; ?>;" />
            </div>
         </div><!-- .row -->
         <div class="row">
            <div class="block">
               <label for="job_phone">Contact Phone Number</label> 
               <input type="text" name="job_phone" id="job_phone" value="<?php echo formatPhone($_POST['job_phone'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block">
               <div class="label">Job Funding</div> 
               <input type="radio" name="job_funding" id="job_funding_state" value="<?php echo JOB_FUNDING_STATE; ?>"<?php echo ($_POST['job_funding']==JOB_FUNDING_STATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_state" class="plain">State</label>
               <input type="radio" name="job_funding" id="job_funding_federal" value="<?php echo JOB_FUNDING_FEDERAL; ?>"<?php echo ($_POST['job_funding']==JOB_FUNDING_FEDERAL) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_federal" class="plain">Federal</label>
               <input type="radio" name="job_funding" id="job_funding_private" value="<?php echo JOB_FUNDING_PRIVATE; ?>"<?php echo ($_POST['job_funding']==JOB_FUNDING_PRIVATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="job_funding_private" class="plain">Private</label>
            </div>
            <div class="block">
                
            </div>
         </div><!-- .row -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Foremen</h2>
         <p>Add as many foremen as necessary.</p>
         
         <div class="row">
            <div class="block full">
                <div id="foremen">
                    <div class="foreman head">
                       <div class="first_name">Foreman First Name</div>
                       <div class="last_name">Foreman Last Name</div>
                    </div>
<?php
        for($i=1; $i<=$num_foremen; $i++){

            include('stewards_report_form_foreman.php');

        }
?>
                </div><!-- div#foremen -->
                <div class="add_foreman">
                    <a href="javascript:addForeman();"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Foreman</a>
                </div>
            </div>
         </div><!-- .row -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Work Descriptions</h2>
         <p>Add as many work descriptions as necessary.</p>
         
         <div class="row">
            <div class="block full">
                <div id="work_descriptions">
<?php
        for($i=1; $i<=$num_work_descriptions; $i++){

            include('stewards_report_form_work_description.php');

        }
?>
                </div><!-- div#work_descriptions -->
                <div class="add_work_description">
                    <a href="javascript:addWorkDescription();"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Work Description</a>
                </div>
            </div>
         </div><!-- .row -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Accidents <em>For Internal Use Only</em></h2>
         <p>Steward must fill out an accident report to Company for any accident occurring on the job. Lost time accidents should be reported to Union Hall. List all accidents, major or minor.</p>
         <div id="accidents">
             <div class="accident head">
               <div class="description">Description of Accident</div>
               <div class="date">Date of Accident</div>
            </div>
<?php
        for($i=1; $i<=$num_accidents; $i++){
            $bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';

            include('stewards_report_form_accident.php');

        }
?>
         </div><!-- div#accidents -->
         <div id="add_accident">
            <a href="javascript:addAccident();"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Accident</a>
         </div>
         
         
         <br style="clear:left;" />
         
         
         <h2>Workers</h2>
         <div id="workers">
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
        for($i=1; $i<=$num_workers; $i++){
            $bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
            
            include('stewards_report_form_worker_v2.php');
        }
?>
                
         </div><!-- div#workers -->
         <div id="workers_total_hours">
            <div class="add_worker">
               <a href="javascript:addWorker();"><img src="../img/icon_plus_blue.jpg" alt="" /> Add Worker</a>
            </div>
            <div class="total_hours_worked">
               <strong>Total Hours Worked</strong> 
               <input type="text" name="total_hours_worked" id="total_hours_worked" readonly="readonly" value="<?php echo $_POST['total_hours_worked']; ?>" />
            </div>
            <div class="total_hours_paid">
               <strong>Total Hours Paid</strong> 
               <input type="text" name="total_hours_paid" id="total_hours_paid" readonly="readonly" value="<?php echo $_POST['total_hours_paid']; ?>" />
            </div>
         </div><!-- div#workers_total_hours -->
         
         
         <br style="clear:left;" />
         
         
         <h2>Job Site Photos</h2>
         <div class="row">
<?php
            for($i=0; $i<$max_photo_uploads; $i++){
                if($i>0 && $i%2==0) echo '</div><div class="row">';
?>
                     <div class="block half">
                         <input type="file" name="photo_<?php echo $i+1; ?>" id="photo_<?php echo $i+1; ?>" size="8" style="width: 175px;" /> 
                         <input type="text" name="caption_<?php echo $i+1; ?>" id="caption_<?php echo $i+1; ?>" value="<?php echo htmlentities($_POST['caption_'.$i+1], ENT_QUOTES); ?>" placeholder="Caption" style="width: 175px;" />
                     </div>
<?php
            }
?>
         </div><!-- .row -->
         
         
         <br style="clear:left;" />
         
         
         <div class="row" style="padding:15px 0;font-weight:bold;">
                 <p>NOTICE - Steward shall examine all Dues Receipts at least once a month. Any member who is thirty days in arrears after his first payday shall be signed up for amount due.</p>
                
            <p>NOTICE - This report must be filed every meeting night with Financial Secretary of Local Union No.3, Pittsburgh, Pennsylvania. Any Steward failing to comply with this Notice will be subject to a fine.</p>
         </div><!-- .row -->
         
         <div class="row">
             <div class="block quarter">
               <label for="steward_name">Steward's Name</label> 
               <input type="text" name="steward_name" id="steward_name" value="<?php echo htmlentities($_POST['steward_name'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_address">Address</label> 
               <input type="text" name="steward_address" id="steward_address" value="<?php echo htmlentities($_POST['steward_address'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_phone">Phone</label> 
               <input type="text" name="steward_phone" id="steward_phone" value="<?php echo htmlentities($_POST['steward_phone'], ENT_QUOTES, 'utf-8'); ?>" />
            </div>
            <div class="block quarter">
               <label for="steward_phone">Email</label> 
               <input type="text" name="steward_email" id="steward_email" value="<?php echo htmlentities($_POST['steward_email'], ENT_QUOTES, 'utf-8'); ?>" /><br>
               
               <input type="checkbox" name="steward_email_subscribe" id="steward_email_subscribe" class="checkbox" value="1"<?php echo ($_POST['steward_email_subscribe'] == 1) ? ' checked="checked"' : ''; ?> /> 
               <label for="steward_email_subscribe" class="normal">Join Email List</label>
            </div>
         </div><!-- .row -->
         
         <div class="row submit">
             <div class="block full">
                 <input type="submit" name="submit" id="submit" value="Submit Report" />
            </div>
         </div><!-- .row -->
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>