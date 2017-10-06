<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	
	// Compile counties into array
	$counties_array = array();
	$counties = getProjectCounties();
	if($counties > 0){
		foreach($counties as $county){
			$counties_array[ $county['county'] ] = $county['county'];
		}
	}
	
	
	// Search fields
	$general_fields = array(
		'type'							=> array(
														'type'=>'radio', 
														'options'=>array(
															STEWARDS_REPORT_TYPE_REGULAR 							=> 'Regular Steward\'s Report ',
															STEWARDS_REPORT_TYPE_PREENGINEERED_METAL	=> 'Pre-Engineered Metal Building Report',
															STEWARDS_REPORT_TYPE_JOB_TARGETED					=> 'Job Targeted Report '
														)
													 ),
		'project_name'			=> array('type'=>'text'),
		'project_location'	=> array('type'=>'text'),
		'project_city'			=> array('type'=>'text'),
		'project_county'		=> array(
														'type'=>'select',
														'attr'=>'multiple',
														'note'=>'Select Multiple',
														'options'=>$counties_array
													 ),
		'project_start_date'=> array('type'=>'range'),
		'pay_period_start'	=> array('type'=>'range'),
		'pay_period_ending'	=> array('type'=>'range'),
		'company'						=> array('type'=>'text'),
		'general_contractor'=> array('type'=>'text'),
		'job_description'		=> array('type'=>'text'),
		'steward_name'			=> array('type'=>'text')
	);
	
	$foremen_fields = array(
		'first_name'				=> array(
														'type'=>'text',
														'options'=>array(
															'prepend'=>'foreman_'
														)
													 ),
		'last_name'					=> array(
														'type'=>'text',
														'options'=>array(
															'prepend'=>'foreman_'
														)
													 )
	);
	
	$workers_fields = array(
		'first_name'				=> array('type'=>'text'),
		'last_name'					=> array('type'=>'text'),
		'local_number'			=> array('type'=>'text'),
		'book_number'				=> array('type'=>'text')
	);
	
	$accidents_fields = array(
		'date'							=> array('type'=>'range')
	);
	
	
	
	// Search (submit form)
	if(isset($_GET['submit'])){
		try {
			$required = array();
						
			$validator = new Validator($required, 'get');
			foreach($_GET as $key=>$value){
				if(is_array($value)){
					$validator->noFilter($key, true);
				} else {
					$validator->noFilter($key);
				}
			}
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				//*** Build Query ***//
				
				// Query selects
				$selects = array();
				$selects[] = 'DISTINCT(`stewards_reports`.`report_id`)';
				
				// Selected fields
				if($_GET['opt_results']){
					foreach($_GET['opt_results'] as $select){
						$selects[] = '`stewards_reports`.`'.$select.'`';
					}
					
				// Default fields
				} else {				
					$selects[] = '`stewards_reports`.`id`';
					$selects[] = '`stewards_reports`.`version`';
					$selects[] = '`stewards_reports`.`project_name`';
					$selects[] = '`stewards_reports`.`company`';
					$selects[] = '`stewards_reports`.`general_contractor`';
					$selects[] = '`stewards_reports`.`date_submitted`';
				}
				
				
				// Query conditions
				$conditions = array();
				
				
				// General fields
				foreach($general_fields as $name=>$options){
					$field_name = prepareStewardsReportFormField($name, $options);
					
					// Date range
					if($options['type'] == 'range'){
						if(!empty($_GET[$field_name.'_from'])){
							$conditions[] = "AND (DATE(`stewards_reports`.`{$name}`)>='".escapeData($_GET[$field_name.'_from'])."' && DATE(`stewards_reports`.`{$name}`)<='".escapeData($_GET[$field_name.'_to'])."')";
						}
						
					// Multiple selections
					} else if($options['type'] == 'select' && $options['attr'] == 'multiple'){
						
						if(!empty($_GET[$field_name])){
							$selections = $_GET[$field_name];
							$selections_batch = array();
							foreach($selections as $selection){
								$selections_batch[] = "`stewards_reports`.`{$name}` LIKE '%".escapeData($selection)."%'";
							}
							$conditions[] = ' AND ('.implode(' OR ', $selections_batch).')';
						}
					
					// Everything else
					} else if(!empty($_GET[$field_name])){
						$conditions[] = "AND `stewards_reports`.`{$name}` LIKE '%".escapeData($_GET[$field_name])."%'";
					}
				}
				
				// Foremen fields
				foreach($foremen_fields as $name=>$options){
					$field_name = prepareStewardsReportFormField($name, $options);
								
					if($options['type'] == 'range'){
						if(!empty($_GET[$field_name.'_from'])){
							$conditions[] = "AND (DATE(`stewards_reports_foremen`.`{$name}`)>='".escapeData($_GET[$field_name.'_from'])."' && DATE(`stewards_reports_foremen`.`{$name}`)<='".escapeData($_GET[$field_name.'_to'])."')";
						}
					} else if(!empty($_GET[$field_name])){
						$conditions[] = "AND `stewards_reports_foremen`.`{$name}` = '".escapeData($_GET[$field_name])."'";
					}
				}
				
				// Worker fields
				foreach($workers_fields as $name=>$options){
					$field_name = prepareStewardsReportFormField($name, $options);
							
					if($options['type'] == 'range'){
						if(!empty($_GET[$field_name.'_from'])){			
							$conditions[] = "AND (DATE(`stewards_reports_workers`.`{$name}`)>='".escapeData($_GET[$field_name.'_from'])."' && DATE(`stewards_reports_workers`.`{$name}`)<='".escapeData($_GET[$field_name.'_to'])."')";
						}
					} else if(!empty($_GET[$field_name])){
						$conditions[] = "AND `stewards_reports_workers`.`{$name}` = '".escapeData($_GET[$field_name])."'";
					}
				}
				
				// Accident fields
				foreach($accidents_fields as $name=>$options){
					$field_name = prepareStewardsReportFormField($name, $options);
							
					if($options['type'] == 'range'){
						if(!empty($_GET[$field_name.'_from'])){		
							$conditions[] = "AND (DATE(`stewards_reports_accidents`.`{$name}`)>='".escapeData($_GET[$field_name.'_from'])."' && DATE(`stewards_reports_accidents`.`{$name}`)<='".escapeData($_GET[$field_name.'_to'])."')";
						}
					} else if(!empty($_GET[$field_name])){
						$conditions[] = "AND `stewards_reports_accidents`.`{$name}` = '".escapeData($_GET[$field_name])."'";
					}
				}
				
				
				// Prepare query
				$query = "
					SELECT ".implode(', ', $selects)." 
					FROM `stewards_reports` 
					LEFT JOIN `stewards_reports_foremen` 
						ON (`stewards_reports`.`report_id` = `stewards_reports_foremen`.`report_id`) 
					LEFT JOIN `stewards_reports_workers` 
						ON (`stewards_reports`.`report_id` = `stewards_reports_workers`.`report_id`) 
					LEFT JOIN `stewards_reports_accidents` 
						ON (`stewards_reports`.`report_id` = `stewards_reports_accidents`.`report_id`) 
					WHERE 1 ".implode(' ', $conditions)." 
					ORDER BY `stewards_reports`.`date_submitted` DESC";
					
				
				// Save query to session for exporting
				$_SESSION['admin']['export_stewards_reports_query'] = $query;
																
				
				// Perform Query
				$results = selectArrayQuery($query);
				if($results > 0){
					$alerts->addAlert('Your search returned '.count($results).' results.', 'success');
				} else {
					$alerts->addAlert('Your search did not return any results.', 'error');
				}
				
			} else {
				$alerts->addAlerts($errors, 'error');
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again.', 'error');
		}
	}
	
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
<link type="text/css" rel="stylesheet" href="/css/stewards_reports.css" />
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.numeric.js"></script>
<script type="text/javascript">
// On document ready...
$(function(){

	// Make sure that specified input is numeric
	$('.numeric').numeric();
	
	
	// Datepickers
	$(".datepicker").datepicker({
			showOn: "both",
			buttonImage: "/img/calendar.gif",
			dateFormat: 'yy-mm-dd'
	});
	
	// Toggle form
	$('#toggle-form').click(function(){
		$('#search-form').children('form').slideToggle();
		$('#toggle-form').html( $('#toggle-form').html()=='Hide Form' ? 'Show Form' : 'Hide Form' );
	});
	
});
</script>
</head>

<body>
<div id="wrapper" class="search">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_search'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<?php /*<a href="../get_excel.php?stewards_reports" class="icon_excel">Download Spreadsheet</a>*/ ?>
      	         
      	<h1>Admin: Search Steward's Reports</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <div id="search-form">
	         <a href="#" id="toggle-form"><?php echo($results > 0) ? 'Show' : 'Hide'; ?> Form</a>
         
	         <form action="<?php echo getSelf(); ?>" method="get"<?php if($results > 0) echo ' class="hide"'; ?>>
	         		
	         		<div class="col-west">
	         			<fieldset>
		         			<h2>General Information</h2>
<?php
				// General Steward's Report Inputs
				foreach($general_fields as $name=>$options){
					echo '
					<div class="'.$options['type'].'">
						'.printStewardsReportFormField($name, $options).'
					</div>';
				}
?>
         				</fieldset>
	         			
	         		</div> <!-- .col-west -->
	         		
	         		<div class="col-east">
	         			<fieldset>
									<h2>Foremen Information</h2>
<?php
				// Foremen Inputs
				foreach($foremen_fields as $name=>$options){
					echo '
					<div class="'.$options['type'].'">
						'.printStewardsReportFormField($name, $options).'
					</div>';
				}
?>
								</fieldset>
							
	         			<fieldset>
		         			<h2>Workers Information</h2>
<?php
				// Workers Inputs
				foreach($workers_fields as $name=>$options){
					echo '
					<div class="'.$options['type'].'">
						'.printStewardsReportFormField($name, $options).'
					</div>';
				}
?>

								</fieldset>
								
								<fieldset>
									<h2>Accidents Information</h2>
<?php
				// Accidents Inputs
				foreach($accidents_fields as $name=>$options){
					echo '
					<div class="'.$options['type'].'">
						'.printStewardsReportFormField($name, $options).'
					</div>';
				}
?>
							</fieldset>
         		</div> <!-- .col-east -->
         		
         		<div class="col-full">
         			<fieldset>
	         			<h2>Select Search Results</h2>
	         			
	         			<p>Select which fields you would like returned from your search.</p>
	         			
	         			<ul>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-id" class="checkbox" value="id"<?php echo (@in_array('id', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-id" class="normal">Report ID</label>
	         				</li>
									<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-date-submitted" class="checkbox" value="date_submitted"<?php echo (@in_array('date_submitted', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-date-submitted" class="normal">Date Submitted/Report Date</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-type" class="checkbox" value="type"<?php echo (@in_array('type', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-type" class="normal">Project Type</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-project-name" class="checkbox" value="project_name"<?php echo (@in_array('project_name', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-project-name" class="normal">Project Name</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-project-location" class="checkbox" value="project_location"<?php echo (@in_array('project_location', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-project-location" class="normal">Project Location</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-project-city" class="checkbox" value="project_city"<?php echo (@in_array('project_city', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-project-city" class="normal">Project City</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-project-county" class="checkbox" value="project_county"<?php echo (@in_array('project_county', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-project-county" class="normal">Project County</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-project-start-date" class="checkbox" value="project_start_date"<?php echo (@in_array('project_start_date', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-project-start-date" class="normal">Project Start Date</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-pay-period-start" class="checkbox" value="pay_period_start"<?php echo (@in_array('pay_period_start', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-pay-period-start" class="normal">Pay Period Start</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-pay-period-ending" class="checkbox" value="pay_period_ending"<?php echo (@in_array('pay_period_ending', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-pay-period-ending" class="normal">Pay Period Ending</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-company" class="checkbox" value="company"<?php echo (@in_array('company', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-company" class="normal">Company</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-general-contractor" class="checkbox" value="general_contractor"<?php echo (@in_array('general_contractor', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-general-contractor" class="normal">General Contractor</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-job-description" class="checkbox" value="job_description"<?php echo (@in_array('job_description', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-job-description" class="normal">Job Description</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-steward-name" class="checkbox" value="steward_name"<?php echo (@in_array('steward_name', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-steward-name" class="normal">Steward Name</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-total-hours-worked" class="checkbox" value="total_hours_worked"<?php echo (@in_array('total_hours_worked', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-total-hours-worked" class="normal">Total Hours Worked</label>
	         				</li>
	         				<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-total-hours-paid" class="checkbox" value="total_hours_paid"<?php echo (@in_array('total_hours_paid', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-total-hours-paid" class="normal">Total Hours Paid</label>
	         				</li>
	         				<!--
									<li>
	         					<input type="checkbox" name="opt_results[]" id="opt-result-foremen" class="checkbox" value="special-foremen"<?php echo (@in_array('special-foremen', $_GET['opt_results'])) ? ' checked="checked"' : ''; ?> /> 
	         					<label for="opt-result-foremen" class="normal">Foreman</label>
	         				</li>
									-->
	         			</ul>
	         			
	         		</fieldset>
	         		
	         		<input type="submit" name="submit" value="Search" />
		        </div> <!-- .col-full -->
	         		
	         </form>
	         
         </div> <!-- #search-form -->
         
         
<?php

			// Search Results
			if(isset($_GET['submit'])){
?>
					<h2>Search Results</h2>
         	<table id="search-results">
         		<tr>
<?php
				// Return fields
				if($_GET['opt_results']){
					foreach($_GET['opt_results'] as $select){
?>
            	<th><?php echo ucwords(str_replace('_', ' ', $select)); ?></th>
<?php
					}
					
				// Default fields
				} else {
?>
               <th>Project Name</th>
               <th>Report Date</th>
               <th>General Contractor</th>
               <th>Company</th>
<?php
				}
?>
               <th width="36">&nbsp;</th>
            </tr>
<?php
				if($results > 0){
					
					$total_paid = 0;
					foreach($results as $result){
						$class = ($class=='odd') ? 'even' : 'odd';
						$ids[] = $result['report_id'];
?>
						<tr class="<?php echo $class; ?>">
<?php
				// Return fields
				if($_GET['opt_results']){
					foreach($_GET['opt_results'] as $select){
						
						// A date
						if(in_array($select, array('date_submitted', 'project_start_date', 'pay_period_start', 'pay_period_ending'))){
?>
							<td><?php echo ($result[$select]) ? date('n/j/Y', strtotime($result[$select])) : ''; ?></td>
<?php							
						
						// Report type
						} else if($select=='type'){
?>
							<td><?php echo printStewardsReportType($result[$select]); ?></td>
<?php					
						
						// `stewards_reports` fields
						} else {
?>
               <td><?php echo (strlen($result[$select]) > 50) ? substr(strip_tags($result[$select]), 0, 50).'…' : $result[$select]; ?></td>
<?php
						
						}
					}
					
				// Default fields
				} else {
?>
               <td><a href="stewards_reports_view<?php echo ($result['version'] != 2) ? '_v1' : ''; ?>.php?rid=<?php echo $result['report_id']; ?>"><?php echo $result['project_name']; ?></a></td>
               <td><?php echo date('n/j/Y', strtotime($result['date_submitted'])); ?></td>
               <td><?php echo $result['general_contractor']; ?></td>
               <td><?php echo $result['company']; ?></td>
<?php
				}
?>
               <td>
               	<a href="stewards_reports_view<?php echo ($result['version'] != 2) ? '_v1' : ''; ?>.php?rid=<?php echo $result['report_id']; ?>" title="View" class="icon_view">View</a> 
               	<a href="/download.php?id=<?php echo $result['report_id']; ?>&amp;t=stewards_reports<?php echo (!empty($_GET['date_from'])) ? '&amp;accidents' : ''; ?>" title="Download PDF" class="icon_pdf">Download PDF</a> 
               </td>
            </tr>
<?php	
					}
?>
						<tfoot>
							<td colspan="100%"><a href="/get_excel.php?stewards_reports" target="_blank" class="export-link"><img src="/img/icon_excel.gif" alt="Export"> Export to Excel</a></td>
						</tfoot>
<?php
				} else {
?>
						<tr>
            	<td>Your search returned 0 results.</td>
            </tr>
<?php	
				}
?>
         	</table>
<?php
			}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>