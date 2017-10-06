<?php

require('config.php');
require("excel.php");

$export_file = "xlsfile:/".HOME_ROOT."inc/export.xls";
$fp = fopen($export_file, "wb");
if (!is_resource($fp))
{
	die("Cannot open $export_file");
}

// Email Registrations
if(isset($_GET['email_registrations'])){
	$require_admin=true; require("authenticate.php");
	require_once('functions/email_registrations.php');

	$file_out = "email_registrations_".date('njy').".xls";

	$emails = getEmailRegistrations();
	if($emails > 0){
		$assoc = array();
		foreach($emails as $email){

			$temp['First Name'] = ucwords($email['first_name']);
			$temp['Last Name'] = ucwords($email['last_name']);
			$temp['Email'] = $email['email'];
			$temp['Date'] = date('n/j/Y', strtotime($email['date']));
			
			array_push($assoc, $temp);
		}
	}
	
// Steward's Reports
} else if(isset($_GET['stewards_reports'])){
	$access_level=ACCESS_LEVEL_BENEFITS_DEPT; require("authenticate.php");
	require_once('functions/stewards_reports.php');

	$file_out = "stewards_reports_".date('njy').".xls";
	
	if($query = $_SESSION['admin']['export_stewards_reports_query']){
		$results = selectArrayQuery($query);
	} else {
		$results = getStewardsReports();
	}
	if($results > 0){
		$assoc = array();
		
		foreach($results as $result){
			$temp = array();
			
			foreach($result as $key=>$value){
				if(is_numeric($key)) continue;
				
				$formatted_key = ucwords(str_replace('_', ' ', $key));
				
				if($key == 'type'){
					$temp[$formatted_key] = printStewardsReportType((int) $value);
				
				} else if($key == 'job_phone' || $key=='steward_phone'){
					$temp[$formatted_key] = formatPhone($value);
				
				} else if($key == 'contractor_welfare_paid'){
					$temp[$formatted_key] = ($value==1) ? 'Yes' : 'No';
				
				} else if($key == 'contractor_welfare_paid_to_month'){
					$temp[$formatted_key] = ($value) ? $month_list[$value] : '';
				
				} else if($key == 'job_funding'){
					switch($value){
					case JOB_FUNDING_STATE :
						$temp[$formatted_key] = 'State';
						break;
					case JOB_FUNDING_FEDERAL :
						$temp[$formatted_key] = 'Federal';
						break;
					case JOB_FUNDING_PRIVATE :
						$temp[$formatted_key] = 'Private';
						break;
					}
				
				} else {
					$temp[$formatted_key] = ucwords($value);
				}
					
			}
			
			array_push($assoc, $temp);
		}
	}
	
	/*
	$ids = (!empty($_GET['ids'])) ? explode('|', $_GET['ids']): false;

	$reports = getStewardsReports('', $ids);
	if($reports > 0){
		$assoc = array();
		foreach($reports as $report){
		
			$report = getStewardsReport($report['report_id']);
			foreach($report as $key=>$value){
			
				if(is_string($key)){
					$formatted_key = ucwords(str_replace('_', ' ', $key));
					
					if($key == 'type'){
						$temp[$formatted_key] = printStewardsReportType($value);
					
					} else if($key == 'job_phone' || $key=='steward_phone'){
						$temp[$formatted_key] = formatPhone($value);
					
					} else if($key == 'contractor_welfare_paid'){
						$temp[$formatted_key] = ($value==1) ? 'Yes' : 'No';
					
					} else if($key == 'contractor_welfare_paid_to_month'){
						$temp[$formatted_key] = ($value) ? $month_list[$value] : '';
					
					} else if($key == 'job_funding'){
						switch($value){
						case JOB_FUNDING_STATE :
							$temp[$formatted_key] = 'State';
							break;
						case JOB_FUNDING_FEDERAL :
							$temp[$formatted_key] = 'Federal';
							break;
						case JOB_FUNDING_PRIVATE :
							$temp[$formatted_key] = 'Private';
							break;
						}
					
					} else {
						$temp[$formatted_key] = ucwords($value);
					}
				}
			}
			
			// $temp['Project Location'] = ucwords($report['project_location']);
			// $temp['Project City'] = ucwords($report['project_city']);
			// $temp['Project Name'] = $report['project_name'];
			// $temp['Project Start Date'] = date('n/j/Y', strtotime($report['project_start_date']));
			// $temp['Steward Name'] = ucwords($report['steward_name']);
			// $temp['Date Submitted'] = date('n/j/Y', strtotime($report['date_submitted']));
			
			array_push($assoc, $temp);
		}
		
	}
	*/
	
// Specific Steward's Report
} else if(isset($_GET['stewards_report']) && !empty($_GET['rid'])){
	$access_level=ACCESS_LEVEL_BENEFITS_DEPT; require("authenticate.php");
	require_once('functions/stewards_reports.php');

	$file_out = "stewards_report_".date('njy').".xls";
	$report_id = (int) $_GET['rid'];

	$report = getStewardsReport($report_id);
	if($report > 0){
		$assoc = array();
		foreach($report as $key=>$value){
			
			if(is_string($key)){
				$formatted_key = ucwords(str_replace('_', ' ', $key));
				
				if($key == 'type'){
					$temp[$formatted_key] = printStewardsReportType($value);
				
				} else if($key == 'job_phone' || $key=='steward_phone'){
					$temp[$formatted_key] = formatPhone($value);
				
				} else if($key == 'contractor_welfare_paid'){
					$temp[$formatted_key] = ($value==1) ? 'Yes' : 'No';
				
				} else if($key == 'contractor_welfare_paid_to_month'){
					$temp[$formatted_key] = ($value) ? $month_list[$value] : '';
				
				} else if($key == 'job_funding'){
					switch($value){
					case JOB_FUNDING_STATE :
						$temp[$formatted_key] = 'State';
						break;
					case JOB_FUNDING_FEDERAL :
						$temp[$formatted_key] = 'Federal';
						break;
					case JOB_FUNDING_PRIVATE :
						$temp[$formatted_key] = 'Private';
						break;
					}
				
				} else {
					$temp[$formatted_key] = ucwords($value);
				}
			}
			
		}
		array_push($assoc, $temp);
	}

} else {
	die("No Data Requested.");
}




fwrite($fp, serialize($assoc));
fclose($fp);


header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"" . $file_out . "\"" );
header ("Content-Description: PHP/INTERBASE Generated Data" );
readfile($export_file);
exit;

?>