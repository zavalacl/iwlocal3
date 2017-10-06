<?php

/* Steward's Reports */
function newStewardsReport($type, $project_location, $project_city, $project_county, $project_name, 
					$project_start_date, $pay_period_ending, $company, $general_contractor, 
					$job_duration, $percent_completed, $contractor_welfare_paid, 
					$contractor_welfare_paid_to_month, $num_ironworkers, $job_phone, 
					$job_funding, $job_description, $foreman_name, $steward_name, 
					$steward_address, $steward_phone, $verbose=false){
	$query = "INSERT INTO `stewards_reports` (`type`, `project_location`, `project_city`, `project_county`, `project_name`, 
					`project_start_date`, `pay_period_ending`, `company`, `general_contractor`, 
					`job_duration`, `percent_completed`, `contractor_welfare_paid`, 
					`contractor_welfare_paid_to_month`, `num_ironworkers`, `job_phone`, 
					`job_funding`, `job_description`, `foreman_name`, `steward_name`, 
					`steward_address`, `steward_phone`, `date_submitted`
				) VALUES (
					'$type', '$project_location', '$project_city', '$project_county', '$project_name', 
					'$project_start_date', '$pay_period_ending', '$company', '$general_contractor', 
					'$job_duration', '$percent_completed', '$contractor_welfare_paid', 
					'$contractor_welfare_paid_to_month', '$num_ironworkers', '$job_phone', 
					'$job_funding', '$job_description', '$foreman_name', '$steward_name', 
					'$steward_address', '$steward_phone', NOW()
				)";
	$result = insertQuery($query, $verbose);
	if($result > 0){
		incrementStewardsReportStat(date('Y'), 'num_reports', 1);
		if(incrementStewardsReportStat(date('Y'), 'reports_counter', 1) > 0){
			setStewardsReportID($result);
		}
	}
	return $result;
}

function newStewardsReportV2($user_id, $type, $project_location, $project_city, $project_county, $project_name, 
					$project_start_date, $pay_period_start, $pay_period_ending, $company_id, $company, $general_contractor, 
					$job_duration, $percent_completed, $contractor_welfare_paid, 
					$contractor_welfare_paid_to_month, $num_ironworkers, $shift, $journeymen_wages_paid, $job_phone, 
					$job_funding, $job_description, $steward_name, $steward_address, $steward_phone, $steward_email, 
					$steward_email_subscribe, $verbose=false){
	$company_id = $company_id ? $company_id : 'NULL';
	
	$query = "INSERT INTO `stewards_reports` (`user_id`, `type`, `project_location`, `project_city`, `project_county`, `project_name`, 
					`project_start_date`, `pay_period_start`, `pay_period_ending`, `company_id`, `company`, `general_contractor`, 
					`job_duration`, `percent_completed`, `contractor_welfare_paid`, 
					`contractor_welfare_paid_to_month`, `num_ironworkers`, `shift`, `journeymen_wages_paid`, `job_phone`, 
					`job_funding`, `job_description`, `steward_name`, `steward_address`, `steward_phone`,  `steward_email`, 
					`steward_email_subscribe`, `date_submitted`
				) VALUES (
					$user_id, '$type', '$project_location', '$project_city', '$project_county', '$project_name', 
					'$project_start_date', '$pay_period_start', '$pay_period_ending', $company_id, '$company', '$general_contractor', 
					'$job_duration', '$percent_completed', '$contractor_welfare_paid', 
					'$contractor_welfare_paid_to_month', '$num_ironworkers', '$shift', '$journeymen_wages_paid', '$job_phone', 
					'$job_funding', '$job_description', '$steward_name', '$steward_address', '$steward_phone',  '$steward_email', 
					'$steward_email_subscribe', NOW()
				)";
	$result = insertQuery($query, $verbose);
	if($result > 0){
		incrementStewardsReportStat(date('Y'), 'num_reports', 1);
		if(incrementStewardsReportStat(date('Y'), 'reports_counter', 1) > 0){
			setStewardsReportID($result);
		}
	}
	return $result;
}

function editStewardsReportV2($report_id, $type, $project_location, $project_city, $project_county, $project_name, 
					$project_start_date, $pay_period_start, $pay_period_ending, $company, $general_contractor, 
					$job_duration, $percent_completed, $contractor_welfare_paid, 
					$contractor_welfare_paid_to_month, $num_ironworkers, $shift, $journeymen_wages_paid, $job_phone, 
					$job_funding, $job_description, $steward_name, $steward_address, $steward_phone, $steward_email, 
					$steward_email_subscribe, $verbose=false){
	$query = "UPDATE `stewards_reports` 
						SET `type`='$type', `project_location`='$project_location', `project_city`='$project_city', `project_county`='$project_county', `project_name`='$project_name',
						`project_start_date`='$project_start_date', `pay_period_start`='$pay_period_start', `pay_period_ending`='$pay_period_ending', `company`='$company', `general_contractor`='$general_contractor', 
						`job_duration`='$job_duration', `percent_completed`='$percent_completed', `contractor_welfare_paid`='$contractor_welfare_paid', 
						`contractor_welfare_paid_to_month`='$contractor_welfare_paid_to_month', `num_ironworkers`='$num_ironworkers', `shift`='$shift', `journeymen_wages_paid`='$journeymen_wages_paid', `job_phone`='$job_phone', 
						`job_funding`='$job_funding', `job_description`='$job_description', `steward_name`='$steward_name', `steward_address`='$steward_address', `steward_phone`='$steward_phone', `steward_email`='$steward_email', 
						`steward_email_subscribe`='$steward_email_subscribe'
						WHERE `report_id`=$report_id
						LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getStewardsReports($limit='', $ids=false, $verbose=false){
	$ids_str = ($ids) ? "WHERE `report_id` IN (".implode(', ', $ids).")" : "";
	
	$query = "SELECT `report_id`, `id`, `version`, `project_location`, `project_city`, `project_name`, `project_start_date`, `steward_name`, `date_submitted`, `num_accidents`, `num_photos`, `num_workers_ssn`, `num_workers_travelers` FROM `stewards_reports` $ids_str ORDER BY `date_submitted` DESC $limit";
	return selectArrayQuery($query, $verbose);
}

function getOwnStewardsReports($user_id, $limit_str='', $ids=false, $verbose=false){
    $ids_str = ($ids) ? "AND `report_id` IN (".implode(', ', $ids).")" : "";
    
    $query = "SELECT `user_id`, `report_id`, `id`, `project_location`, `project_city`, `project_name`, `project_start_date`, `steward_name`, `date_submitted`, `num_accidents`, `num_photos`, `num_workers_ssn`, `num_workers_travelers`, `pay_period_start`, `pay_period_ending` FROM `stewards_reports` WHERE `user_id`=$user_id $ids_str ORDER BY `date_submitted` DESC $limit_str";
    return selectArrayQuery($query, $verbose);
}

function countStewardsReports($verbose=false){
	$query = "SELECT COUNT(`report_id`) FROM `stewards_reports`";
	$result = selectQuery($query, $verbose);
	if($result > 0)
		return $result[0];
	else
		return $result;
}

function getStewardsReport($report_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports` WHERE `report_id`=$report_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function setStewardsReportJobDescriptions($report_id, $job_description, $verbose=false){
	$query = "UPDATE `stewards_reports` SET `job_description`='$job_description' WHERE `report_id`=$report_id LIMIT 1";
	return query($query, $verbose);
}

function setStewardsReportValue($report_id, $column, $value, $verbose=false){
	$query = "UPDATE `stewards_reports` SET `{$column}`='$value' WHERE `report_id`=$report_id LIMIT 1";
	return query($query, $verbose);
}

function incrementStewardsReportValue($report_id, $column, $verbose=false){
	$query = "UPDATE `stewards_reports` SET `{$column}`=`{$column}`+1 WHERE `report_id`=$report_id LIMIT 1";
	return query($query, $verbose);
}

function decrementStewardsReportValue($report_id, $column, $verbose=false){
	$query = "UPDATE `stewards_reports` SET `{$column}`=`{$column}`-1 WHERE `report_id`=$report_id LIMIT 1";
	return query($query, $verbose);
}

function updateStewardsReportTotalHours($report_id, $verbose=false){

	// Update report to include total worker hours worked and paid
	$total_hours = selectQuery("SELECT SUM(`hours_worked`) AS `total_hours_worked`, SUM(`hours_paid`) AS `total_hours_paid` FROM `stewards_reports_workers` WHERE `report_id`=$report_id", $verbose);
	$total_hours_worked = $total_hours['total_hours_worked'];
	$total_hours_paid = $total_hours['total_hours_paid'];

	return query("UPDATE `stewards_reports` SET `total_hours_worked`='$total_hours_worked', `total_hours_paid`='$total_hours_paid' WHERE `report_id`=$report_id LIMIT 1", $verbose);
}

function deleteStewardsReport($report_id, $verbose=false){
	$report_info = getStewardsReport($report_id, $verbose);
	
	$query = "DELETE FROM `stewards_reports` WHERE `report_id`=$report_id LIMIT 1";
	$result = query($query, $verbose);
	
	if($result > 0){
		if($report_info > 0){
			decrementStewardsReportStat(date('Y', strtotime($report_info['date_submitted'])), 'num_reports', 1);
		}
		deleteStewardsReportWorkers($report_id, $verbose);
		deleteStewardsReportAccidents($report_id, $verbose);
		deleteStewardsReportPhotos($report_id, $verbose);
		deleteStewardsReportForemen($report_id, $verbose);
	}
	return $result;
}

function printStewardsReportType($type){
	switch($type){
	case STEWARDS_REPORT_TYPE_REGULAR : 
		return 'Regular';
	case STEWARDS_REPORT_TYPE_PREENGINEERED_METAL :
		return 'Pre-Engineered Metal Building';
	case STEWARDS_REPORT_TYPE_JOB_TARGETED :
		return 'Job Targeted';
	default :
		return 'N/A';
	}
}

function printFundingType($type){
	switch($type){
	case JOB_FUNDING_STATE :
		return 'State';
	case JOB_FUNDING_FEDERAL :
		return 'Federal';
	case JOB_FUNDING_PRIVATE :
		return 'Private';
	default :
		return 'N/A';
	}
}

function setStewardsReportID($report_id, $verbose=false){
	$id = generateStewardsReportID($report_id, $verbose);
	if($id > 0){
		$query = "UPDATE `stewards_reports` SET `id`='$id' WHERE `report_id`=$report_id LIMIT 1";
		return query($query, $verbose);
	} else {
		return $id;
	}
}

function generateStewardsReportID($report_id, $verbose=false){
	$report_info = getStewardsReport($report_id, $verbose);
	if($report_info > 0){
		$report_year = date('Y', strtotime($report_info['date_submitted']));
		$stats = getStewardsReportsStatsYear($report_year, $verbose);
		
		return $report_year.'-'.$stats['reports_counter'];
	} else {
		return $report_info;
	}
}




/* Steward's Report Workers */
function newStewardsReportWorker($report_id, $first_name, $last_name, $local_number, $type, $book_number, $month_dues_paid, $hours_paid, $hours_worked=0, $time_straight=0, $time_half=0, $time_double=0, $is_ssn=false, $verbose=false){
	$is_ssn = ($is_ssn) ? 1 : 0; // Is the book number a SSN?
	$query = "INSERT INTO `stewards_reports_workers` (`report_id`, `first_name`, `last_name`, `local_number`, `type`, `book_number`, `is_ssn`, `month_dues_paid`, `hours_paid`, `hours_worked`, `time_straight`, `time_half`, `time_double`, `report_date`) VALUES ($report_id, '$first_name', '$last_name', '$local_number', $type, '$book_number', $is_ssn, '$month_dues_paid', '$hours_paid', '$hours_worked', '$time_straight', '$time_half', '$time_double', CURDATE())";
	return insertQuery($query, $verbose);
}

function editStewardsReportWorker($worker_id, $first_name, $last_name, $local_number, $type, $month_dues_paid, $hours_paid, $hours_worked=0, $time_straight=0, $time_half=0, $time_double=0, $verbose=false){
	$query = "UPDATE `stewards_reports_workers` SET `first_name`='$first_name', `last_name`='$last_name', `local_number`='$local_number', `type`='$type', `month_dues_paid`='$month_dues_paid', `hours_paid`='$hours_paid', `hours_worked`='$hours_worked', `time_straight`='$time_straight', `time_half`='$time_half', `time_double`='$time_double' WHERE `worker_id`=$worker_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getStewardsReportWorkers($report_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_workers` WHERE `report_id`=$report_id ORDER BY `last_name` ASC, `first_name` ASC";
	return selectArrayQuery($query, $verbose);
}

function getStewardsReportWorkerMostRecentMonthDuesPaid($book_number, $verbose=false){
	$query = "SELECT `month_dues_paid` FROM `stewards_reports_workers` WHERE `book_number`=$book_number ORDER BY `month_dues_paid` DESC";
	$result = selectQuery($query, $verbose);
	if($result > 0){
		return $result[0];
	}
	return null;
}

function deleteStewardsReportWorker($worker_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_workers` WHERE `worker_id`=$worker_id LIMIT 1";
	return query($query, $verbose);
}

function deleteStewardsReportWorkers($report_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_workers` WHERE `report_id`=$report_id";
	return query($query, $verbose);
}

function printWorkerType($type){
	switch($type){
	case WORKER_TYPE_JOURNEYMAN :
		return 'Journeyman';
	case WORKER_TYPE_APPRENTICE :
		return 'Apprentice';
	case WORKER_TYPE_PROBATIONARY :
		return 'Probationary';
	default :
		return 'Unknown';
	}
}




/* Steward's Report Accidents */
function newStewardsReportAccident($report_id, $description, $date, $verbose=false){
	$query = "INSERT INTO `stewards_reports_accidents` (`report_id`, `description`, `date`) VALUES ($report_id, '$description', '$date')";
	return insertQuery($query, $verbose);
}

function editStewardsReportAccident($accident_id, $description, $date, $verbose=false){
	$query = "UPDATE `stewards_reports_accidents` SET `description`='$description', `date`='$date' WHERE `accident_id`=$accident_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getStewardsReportAccidents($report_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_accidents` WHERE `report_id`=$report_id ORDER BY `date` DESC";
	return selectArrayQuery($query, $verbose);
}

function deleteStewardsReportAccident($accident_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_accidents` WHERE `accident_id`=$accident_id LIMIT 1";
	return query($query, $verbose);
}

function deleteStewardsReportAccidents($report_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_accidents` WHERE `report_id`=$report_id";
	return query($query, $verbose);
}




/* Steward's Reports PDFs */
function createStewardsReportPDF($report_id, $include_accidents=false, $verbose=false){
	require('classes/PDF_MC_Table.php');
	global $file_paths;
	global $month_list;
	
	$page_width = 192; // mm
	
	$report_info = getStewardsReport($report_id, $verbose);
	
	$pdf = new PDF_MC_Table('P', 'mm', 'Letter');
	$pdf->setTitle("Steward's Report");
	$pdf->setCreator("Iron Workers Local Union No. 3");
	
	$pdf->AddPage();
	$pdf->SetFont('Helvetica', 'B');
	
	/* Body Start */
	
	// Header
	$pdf->SetFontSize('10');
	$pdf->Write(8, "INTERNATIONAL ASSOCIATION OF BRIDGE, STRUCTURAL, ORNAMENTAL, AND REINFORCING IRON WORKERS\n");
	
	$pdf->SetFontSize('20');
	$pdf->Cell($page_width, 10, "STEWARD’S REPORT", 'B', 2, 'C');
		
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize('10');
	
	$pdf->Write(12, "Submitted on ".date('F j, Y \a\t g:ia', strtotime($report_info['date_submitted']))."       Type: ".printStewardsReportType($report_info['type'])." Report\n");
	
	
	// Project Information
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Project Location\n".$report_info['project_location'], "Project City\n".$report_info['project_city'], "Project County\n".$report_info['project_county']));
	
	$pdf->SetWidths(array(120, 36, 36));
	$pdf->Row(array("Name of Project\n".$report_info['project_name'], "Project Start Date\n".date('F j, Y', strtotime($report_info['project_start_date'])), "Pay Period Ending\n".date('F j, Y', strtotime($report_info['pay_period_ending']))));
	
	$pdf->SetWidths(array(120, 72));
	$pdf->Row(array("Company - In which your are employed\n".$report_info['company'], "General Contractor\n".$report_info['general_contractor']));
	
	$pdf->SetWidths(array(38, 38, 68, 48));
	$pdf->Row(array("Duration of Job\n".$report_info['job_duration'], "% of Completion\n".$report_info['percent_completed'], "Contractors Welfare/Pension Paid Up?\n".(($report_info['contractor_welfare_paid']) ? 'Yes' : 'No. ').((!$report_info['contractor_welfare_paid']) ? ' Paid to: '.$month_list[$report_info['contractor_welfare_paid_to_month']] : ''), "No. of Iron Workers on Job\n".$report_info['num_ironworkers']));
	
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Job Phone\n".formatPhone($report_info['job_phone']), "Job Funding\n".printFundingType($report_info['job_funding']), "Foreman's Name\n".$report_info['foreman_name']));
	
	$pdf->SetWidths(array(192));
	$pdf->Row(array("Description of Work\n".$report_info['job_description']));
	
	
	// Accidents
	if($include_accidents){
	
		$pdf->SetFont('Helvetica', 'B');
		$pdf->SetFontSize('12');
		
		$pdf->Ln();
		$pdf->Cell($page_width, 8, "Accidents", 0, 1, 'L');
		
		$pdf->Line($pdf->getX(), $pdf->getY(), $page_width, $pdf->getY());
		
		$pdf->SetFont('Helvetica');
		$pdf->SetFontSize('10');
		
		$pdf->SetWidths(array(140, 52));
		$pdf->SetFills(array(true, true));
		$pdf->SetTextColor(255);
		$pdf->Row(array("Description of Accident", "Date of Accident"));
		$pdf->SetFills(array(false, false));
		$pdf->SetTextColor(0);
			
		$accidents = getStewardsReportAccidents($report_id);
		if($accidents > 0){
			foreach($accidents as $accident){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
				
			$pdf->SetWidths(array(140, 52));
			$pdf->Row(array($accident['description'], date('F j, Y', strtotime($accident['date']))));
		
			}
		}
	}


	// Workers
	$pdf->SetFont('Helvetica', 'B');
	$pdf->SetFontSize('12');
	
	$pdf->Ln();
	$pdf->Cell($page_width, 8, "Workers", 0, 1, 'L');
	
	$pdf->Line($pdf->getX(), $pdf->getY(), $page_width, $pdf->getY());
	
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize('10');
	
	$pdf->SetWidths(array(32, 32, 23, 27, 24, 27, 27));
	$pdf->SetFills(array(true, true, true, true, true, true, true));
	$pdf->SetTextColor(255);
	$pdf->Row(array("First Name", "Last Name", "Local No.", "Type", "Book No.", "Month Paid", "Ttl Hrs Paid"));
	$pdf->SetFills(array(false, false, false, false, false, false, false));
	$pdf->SetTextColor(0);


	$workers = getStewardsReportWorkers($report_id);
	if($workers > 0){
		
		$total_hours = 0;
		foreach($workers as $worker){
			$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
			$total_hours += $worker['hours_paid'];
			
			$book_number = ($worker['is_ssn']) ? '*********' : $worker['book_number']; // SSN?
			
	$pdf->SetWidths(array(32, 32, 23, 27, 24, 27, 27));
	$pdf->Row(array($worker['first_name'], $worker['last_name'], $worker['local_number'], printWorkerType($worker['type']), $book_number, $worker['month_dues_paid'], $worker['hours_paid']));

		}
		
	$pdf->SetWidths(array(165, 27));
	$pdf->SetAligns(array('R', 'L'));
	$pdf->Row(array("Total Hours", number_format($total_hours, 2)));
	
	}
	
	$pdf->Ln();
	
	
	// Steward Info
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->SetAligns(array('L', 'L', 'L'));
	$pdf->Row(array("Steward's Name\n".$report_info['steward_name'], "Address\n".$report_info['steward_address'], "Phone\n".formatPhone($report_info['steward_phone'])));
			
	/* Body End */
		
	$filename = uniqid($report_id.time()).'.pdf';
	$pdf->Output($file_paths['stewards_reports'].$filename, 'F');	
	
	return $filename;
}



/* Steward's Reports PDFs (VERSION 2) */
function createStewardsReportPDFV2($report_id, $include_accidents=false, $verbose=false){
	require('classes/PDF_MC_Table.php');
	global $file_paths;
	global $month_list;
	
	$page_width = 192; // mm
	
	$report_info = getStewardsReport($report_id, $verbose);
	
	$pdf = new PDF_MC_Table('P', 'mm', 'Letter');
	$pdf->setTitle("Steward's Report");
	$pdf->setCreator("Iron Workers Local Union No. 3");
	
	$pdf->AddPage();
	$pdf->SetFont('Helvetica', 'B');
	
	/* Body Start */
	
	// Header
	$pdf->SetFontSize('10');
	$pdf->Write(8, "INTERNATIONAL ASSOCIATION OF BRIDGE, STRUCTURAL, ORNAMENTAL, AND REINFORCING IRON WORKERS\n");
	
	$pdf->SetFontSize('20');
	$pdf->Cell($page_width, 10, "STEWARD’S REPORT", 'B', 2, 'C');
		
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize('10');
		
	// Report Date and IDs
	$pdf->SetWidths(array(96, 96));
	$pdf->Row(array("Submitted on ".date('F j, Y \a\t g:ia', strtotime($report_info['date_submitted'])), "Report IDs: ".$report_info['report_id'].'/'.$report_info['id']));
	
	// Report Type
	$pdf->SetWidths(array(192));
	$pdf->Row(array("Type: ".printStewardsReportType($report_info['type'])));
	
	// Report Type
	$pdf->SetWidths(array(192));
	$pdf->Row(array("Type: ".$report_info['type']));
	
	// Project Name/Description
	$pdf->SetWidths(array(192));
	$pdf->Row(array("Name/Description of Project\n".$report_info['project_name']));
	
	// Project Information
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Project Address/Location\n".$report_info['project_location'], "Project City\n".$report_info['project_city'], "Project County\n".$report_info['project_county']));
	
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Project Start Date\n".( ($report_info['project_start_date']) ? date('F j, Y', strtotime($report_info['project_start_date'])) : ''), "Pay Period Start\n".(($report_info['pay_period_start']) ? date('F j, Y', strtotime($report_info['pay_period_start'])) : ''), "Pay Period End\n".date('F j, Y', strtotime($report_info['pay_period_ending']))));
	
	$pdf->SetWidths(array(120, 72));
	$pdf->Row(array("Company - In which your are employed\n".$report_info['company'], "General Contractor\n".$report_info['general_contractor']));
	
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Duration of Job\n".$report_info['job_duration'], "% of Completion\n".$report_info['percent_completed']."%", "Contractors Welfare/Pension Paid Up?\n".(($report_info['contractor_welfare_paid']) ? 'Yes' : 'No. ').((!$report_info['contractor_welfare_paid']) ? ' Paid to: '.$month_list[$report_info['contractor_welfare_paid_to_month']] : '')));
	
	$pdf->SetWidths(array(79, 34, 79));
	$pdf->Row(array("Total No. of Iron Workers on this Report\n".$report_info['num_ironworkers'], "Shift\n".$report_info['shift'], "Journeymen Wages on this Project Paid at\n".$report_info['journeymen_wages_paid']));
	
	$pdf->SetWidths(array(64, 64, 64));
	$pdf->Row(array("Contact Phone Number\n".formatPhone($report_info['job_phone']), "Job Funding\n".printFundingType($report_info['job_funding']), " "));
	
	
	// Foremen
	$foremen = getStewardsReportForemen($report_id);
	if($foremen > 0){
		
		$foreman_bunch = array();
		
		foreach($foremen as $foreman){
			$foreman_bunch[] = $foreman['first_name'].' '.$foreman['last_name'];
		}
		
		$pdf->SetWidths(array(192));
		$pdf->Row(array("Foremen\n" . implode(', ', $foreman_bunch) ));
	}
	
	
	// Work Descriptions
	$pdf->SetWidths(array(192));
	$pdf->Row(array("Description of Work\n".$report_info['job_description']));
	
	
	// Accidents
	if($include_accidents){
	
		$accidents = getStewardsReportAccidents($report_id);
		if($accidents > 0){
		
			$pdf->SetFont('Helvetica', 'B');
			$pdf->SetFontSize('12');
			
			$pdf->Ln();
			$pdf->Cell($page_width, 8, "Accidents", 0, 1, 'L');
			
			$pdf->Line($pdf->getX(), $pdf->getY(), $page_width, $pdf->getY());
			
			$pdf->SetFont('Helvetica');
			$pdf->SetFontSize('10');
			
			$pdf->SetWidths(array(140, 52));
			$pdf->SetFills(array(true, true));
			$pdf->SetTextColor(255);
			$pdf->Row(array("Description of Accident", "Date of Accident"));
			$pdf->SetFills(array(false, false));
			$pdf->SetTextColor(0);
			
		
			foreach($accidents as $accident){
				$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
				
				$pdf->SetWidths(array(140, 52));
				$pdf->Row(array($accident['description'], date('F j, Y', strtotime($accident['date']))));
			}
		}
	}


	// Workers
	$pdf->SetFont('Helvetica', 'B');
	$pdf->SetFontSize('12');
	
	$pdf->Ln();
	$pdf->Cell($page_width, 8, "Workers", 0, 1, 'L');
	
	$pdf->Line($pdf->getX(), $pdf->getY(), $page_width, $pdf->getY());
	
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize('9');
	
	$pdf->SetWidths(array(7, 30, 10, 22, 18, 20, 20, 16, 16, 16, 17));
	$pdf->SetFills(array(true, true, true, true, true, true, true, true, true, true, true));
	$pdf->SetTextColor(255);
	$pdf->Row(array(" ", "Name", "Local", "Type", "Book No.", "Mn. Paid", "Hrs Worked", "Hrs Paid", "Str. Time", "Time 1/2", "Dbl. Time"));
	$pdf->SetFills(array(false, false, false, false, false, false, false, false, false, false, false));
	$pdf->SetTextColor(0);


	$workers = getStewardsReportWorkers($report_id);
	if($workers > 0){
		
		$total_hours = 0;
		$i=1;
		foreach($workers as $worker){
			$bkgd = ($bkgd=='#fff') ? '#efefef' : '#fff';
			$total_hours_worked += $worker['hours_worked'];
			$total_hours_paid += $worker['hours_paid'];
			
			$book_number = ($worker['is_ssn']) ? '*********' : $worker['book_number']; // SSN?
			
	$pdf->SetWidths(array(7, 30, 10, 22, 18, 20, 20, 16, 16, 16, 17));
	$pdf->Row(array($i, $worker['first_name'].' '.$worker['last_name'], $worker['local_number'], printWorkerType($worker['type']), $book_number, $worker['month_dues_paid'], $worker['hours_worked'], $worker['hours_paid'], $worker['time_straight'], $worker['time_half'], $worker['time_double']));

			$i++;
		}
	
	$pdf->SetFont('Helvetica', 'B');
		
	$pdf->SetWidths(array(107, 20, 16, 49));
	$pdf->SetAligns(array('R', 'L'));
	$pdf->Row(array(" ", $total_hours_worked, $total_hours_paid, " "));
	
	}
	
	$pdf->Ln();
	
	
	// Steward Info
	$pdf->SetFont('Helvetica');
	$pdf->SetFontSize('10');
	
	$pdf->SetWidths(array(48, 48, 48, 48));
	$pdf->SetAligns(array('L', 'L', 'L', 'L'));
	$pdf->Row(array("Steward's Name\n".$report_info['steward_name'], "Address\n".$report_info['steward_address'], "Phone\n".formatPhone($report_info['steward_phone']), "Email\n".$report_info['steward_email']."\nJoin Email List: ".(($report_info['steward_email_subscribe']) ? 'Yes' : 'No')));
			
	/* Body End */
		
	$filename = uniqid($report_id.time()).'.pdf';
	$pdf->Output($file_paths['stewards_reports'].$filename, 'F');	
	
	return $filename;
}




/* Work Description Categories */
function newWorkDescriptionCategory($category, $verbose=false){
	$query = "INSERT INTO `stewards_reports_work_description_categories` (`category`) VALUES ('$category')";
	return insertQuery($query, $verbose);
}

function editWorkDescriptionCategory($category_id, $category, $verbose=false){
	$query = "UPDATE `stewards_reports_work_description_categories` SET `category`='$category' WHERE `category_id`=$category_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getWorkDescriptionCategories($verbose=false){
	$query = "SELECT * FROM `stewards_reports_work_description_categories` ORDER BY `category` ASC";
	return selectArrayQuery($query, $verbose);
}

function getWorkDescriptionCategory($category_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_work_description_categories` WHERE `category_id`=$category_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function deleteWorkDescriptionCategory($category_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_work_description_categories` WHERE `category_id`=$category_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		deleteWorkDescriptionItems($category_id, false, $verbose);
	}
	return $result;
}




/* Work Description Items */
function newWorkDescriptionItem($category_id, $subcategory_id, $item, $describe=false, $verbose=false){
	$subcategory_id = ($subcategory_id) ? $subcategory_id : "NULL";
	$describe = ($describe) ? 1 : 0;
	$query = "INSERT INTO `stewards_reports_work_description_items` (`category_id`, `subcategory_id`, `item`, `describe`) VALUES ($category_id, $subcategory_id, '$item', $describe)";
	return insertQuery($query, $verbose);
}

function editWorkDescriptionItem($item_id, $item, $describe, $verbose=false){
	$describe = ($describe) ? 1 : 0;
	$query = "UPDATE `stewards_reports_work_description_items` SET `item`='$item', `describe`=$describe WHERE `item_id`=$item_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getWorkDescriptionItems($category_id, $subcategory_id=false, $verbose=false){
	$subcategory_id = ($subcategory_id) ? "AND `subcategory_id`=$subcategory_id" : "AND `subcategory_id` IS NULL";
	$query = "SELECT * FROM `stewards_reports_work_description_items` WHERE `category_id`=$category_id $subcategory_id ORDER BY `item` ASC";
	return selectArrayQuery($query, $verbose);
}

function getWorkDescriptionItem($item_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_work_description_items` WHERE `item_id`=$item_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getWorkDescriptionItemParent($item_id, $verbose=false){
	$item_info = getWorkDescriptionItem($item_id, $verbose);
	if($item_info['subcategory_id'])
		return getWorkDescriptionItem($item_info['subcategory_id']);
	else
		return false;
}

function getWorkDescriptionItemsParentsRecursive($item_id, $batch=array(), $verbose=false){
	$parent = getWorkDescriptionItemParent($item_id, $verbose);
	if($parent > 0){
		$batch[] = $parent['item_id'];
		return getWorkDescriptionItemsParentsRecursive($parent['item_id'], $batch, $verbose);
	} else {	
		return array_reverse($batch);
	}
}

function getWorkDescriptionTreeAsString($item_id, $category_id, $last=false, $description=false, $verbose=false){
	$category_info = getWorkDescriptionCategory($category_id);
	
	$branches = array();
	$branches[] = $category_info['category'];
	
	if($item_id){
		$item_info = getWorkDescriptionItem($item_id, $verbose);
		
		$parents = getWorkDescriptionItemsParentsRecursive($item_id, array(), $verbose);
		if($parents){
			foreach($parents as $parent){
				$parent_info = getWorkDescriptionItem($parent, $verbose);
				if($parent_info > 0){
					$branches[] = $parent_info['item'];
				}
			}
		}
		
		$branches[] = $item_info['item'];
	}
	
	if($last) $branches[] = $last;
		
	$tree = implode(' - ', $branches);
	if($description) $tree .= ' ('.$description.')';
	
	return $tree;
}

function deleteWorkDescriptionItem($item_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_work_description_items` WHERE `item_id`=$item_id LIMIT 1";
	return query($query, $verbose);
}

function deleteWorkDescriptionItems($category_id, $subcategory_id=false, $verbose=false){
	$items = getWorkDescriptionItems($category_id, $subcategory_id, $verbose);
	if($items > 0){
		foreach($items as $item){
			deleteWorkDescriptionItem($item['item_id'], $verbose);
		}
		return 1;
	} else {
		return $items;
	}
}




/* Foremen */
function newStewardsReportForeman($report_id, $first_name, $last_name, $verbose=false){
	$query = "INSERT INTO `stewards_reports_foremen` (`report_id`, `first_name`, `last_name`) VALUES ($report_id, '$first_name', '$last_name')";
	return query($query, $verbose);
}

function editStewardsReportForeman($foreman_id, $first_name, $last_name, $verbose=false){
	$query = "UPDATE `stewards_reports_foremen` SET `first_name`='$first_name', `last_name`='$last_name' WHERE `foreman_id`=$foreman_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getStewardsReportForeman($foreman_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_foremen` WHERE `foreman_id`=$foreman_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getStewardsReportForemen($report_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_foremen` WHERE `report_id`=$report_id ORDER BY `last_name` ASC, `first_name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteStewardsReportForemen($report_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_foremen` WHERE `report_id`=$report_id";
	return query($query, $verbose);
}




/* Projects */
function newProject($name, $address, $city, $county, $funding, $verbose=false){
	$query = "INSERT INTO `stewards_reports_projects` (`name`, `address`, `city`, `county`, `funding`) VALUES ('$name', '$address', '$city', '$county', '$funding')";
	return insertQuery($query, $verbose);
}

function editProject($project_id, $name, $address, $city, $county, $funding, $verbose=false){
	$query = "UPDATE `stewards_reports_projects` SET `name`='$name', `address`='$address', `city`='$city', `county`='$county', `funding`='$funding' WHERE `project_id`=$project_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getProject($project_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects` WHERE `project_id`=$project_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getProjects($verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects` ORDER BY `name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteProject($project_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_projects` WHERE `project_id`=$project_id LIMIT 1";
	return query($query, $verbose);
}





/* Project Counties */
function newProjectCounty($county, $verbose=false){
	$query = "INSERT INTO `stewards_reports_projects_counties` (`county`) VALUES ('$county')";
	return insertQuery($query, $verbose);
}

function editProjectCounty($county_id, $county, $verbose=false){
	$query = "UPDATE `stewards_reports_projects_counties` SET `county`='$county' WHERE `county_id`=$county_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getProjectCounty($county_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects_counties` WHERE `county_id`=$county_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getProjectCounties($verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects_counties` ORDER BY `county` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteProjectCounty($county_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_projects_counties` WHERE `county_id`=$county_id LIMIT 1";
	return query($query, $verbose);
}





/* Companies */
/*
function newCompany($name, $verbose=false){
	$query = "INSERT INTO `stewards_reports_companies` (`name`) VALUES ('$name')";
	return insertQuery($query, $verbose);
}

function editCompany($company_id, $name, $verbose=false){
	$query = "UPDATE `stewards_reports_companies` SET `name`='$name' WHERE `company_id`=$company_id LIMIT 1";
	return booleanQuery($query, $verbose);
}
*/

function getCompany($company_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_companies` WHERE `company_id`=$company_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getCompanies($verbose=false){
	$query = "SELECT * FROM `stewards_reports_companies` ORDER BY `name` ASC";
	return selectArrayQuery($query, $verbose);
}

/*
function deleteCompany($company_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_companies` WHERE `company_id`=$company_id LIMIT 1";
	return query($query, $verbose);
}
*/





/* General Contractors */
function newGeneralContractor($name, $verbose=false){
	$query = "INSERT INTO `stewards_reports_contractors` (`name`) VALUES ('$name')";
	return insertQuery($query, $verbose);
}

function editGeneralContractor($contractor_id, $name, $verbose=false){
	$query = "UPDATE `stewards_reports_contractors` SET `name`='$name' WHERE `contractor_id`=$contractor_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getGeneralContractor($contractor_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_contractors` WHERE `contractor_id`=$contractor_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getGeneralContractors($verbose=false){
	$query = "SELECT * FROM `stewards_reports_contractors` ORDER BY `name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteGeneralContractor($contractor_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_contractors` WHERE `contractor_id`=$contractor_id LIMIT 1";
	return query($query, $verbose);
}

function deleteGeneralContractors($verbose=false){
	$query = "DELETE FROM `stewards_reports_contractors`";
	return query($query, $verbose);
}





/* Locations */
function newProjectLocation($address, $city, $county, $verbose=false){
	$query = "INSERT INTO `stewards_reports_projects_locations` (`address`, `city`, `county`) VALUES ('$address', '$city', '$county')";
	return insertQuery($query, $verbose);
}

function editProjectLocation($location_id, $address, $city, $county, $verbose=false){
	$query = "UPDATE `stewards_reports_projects_locations` SET `address`='$address', `city`='$city', `county`='$county' WHERE `location_id`=$location_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function getProjectLocation($location_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects_locations` WHERE `location_id`=$location_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getProjectLocations($verbose=false){
	$query = "SELECT * FROM `stewards_reports_projects_locations` ORDER BY `address` ASC, `city` ASC, `county` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteProjectLocation($location_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_projects_locations` WHERE `location_id`=$location_id LIMIT 1";
	return query($query, $verbose);
}




/* Employers */
function newEmployer($employer_id, $name, $paid_through, $verbose=false){
	$query = "INSERT INTO `stewards_reports_employers` (`employer_id`, `name`, `paid_through`) VALUES ($employer_id, '$name', '$paid_through')";
	return query($query, $verbose);
}

function editEmployer($employer_id, $name, $paid_through, $verbose=false){
	$query = "UPDATE `stewards_reports_employers` SET `name`='$name', `paid_through`='$paid_through' WHERE `employer_id`=$employer_id LIMIT 1";
	return booleanQuery($query, $verbose);
}

function updateEmployerPaidThroughDate($employer_id, $paid_through, $verbose=false){
	$query = "UPDATE `stewards_reports_employers` SET `paid_through`='$paid_through' WHERE `employer_id`=$employer_id LIMIT 1";
	return query($query, $verbose);
}

function getEmployer($employer_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_employers` WHERE `employer_id`=$employer_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getEmployerByIdOrName($employer_id, $name, $verbose=false){
	if($employer_id){
		return getEmployer($employer_id, $verbose);
	}
	
	$query = "SELECT * FROM `stewards_reports_employers` WHERE `name`='$name' ORDER BY `paid_through` DESC LIMIT 1";
	return selectQuery($query, $verbose);
}

function getEmployers($verbose=false){
	$query = "SELECT * FROM `stewards_reports_employers` ORDER BY `name` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteEmployer($employer_id, $verbose=false){
	$query = "DELETE FROM `stewards_reports_employers` WHERE `employer_id`=$employer_id LIMIT 1";
	return query($query, $verbose);
}

function deleteEmployers($verbose=false){
	$query = "DELETE FROM `stewards_reports_employers`";
	return query($query, $verbose);
}




/* Photos */
function newStewardsReportPhoto($report_id, $filename, $caption, $verbose=false){
	$query = "INSERT INTO `stewards_reports_photos` (`report_id`, `filename`, `caption`) VALUES ($report_id, '$filename', '$caption')";
	return insertQuery($query, $verbose);
}

function getStewardsReportPhoto($photo_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_photos` WHERE `photo_id`=$photo_id LIMIT 1";
	return selectQuery($query, $verbose);
}

function getStewardsReportPhotos($report_id, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_photos` WHERE `report_id`=$report_id ORDER BY `report_id` ASC";
	return selectArrayQuery($query, $verbose);
}

function deleteStewardsReportPhoto($photo_id, $verbose=false){
	$photo_info = getStewardsReportPhoto($photo_id, $verbose);
	$query = "DELETE FROM `stewards_reports_photos` WHERE `photo_id`=$photo_id LIMIT 1";
	$result = query($query, $verbose);
	if($result > 0){
		global $file_paths;
		unlink($file_paths['stewards_reports_photos'].$photo_info['filename']);
	}
	return $result;
}

function deleteStewardsReportPhotos($report_id, $verbose=false){
	$photos = getStewardsReportPhotos($report_id, $verbose);
	if($photos > 0){
		foreach($photos as $photo){
			deleteStewardsReportPhoto($photo['photo_id'], $verbose);
		}
		return 1;
	} else {
		return 0;
	}
}




/* Stats */
function newStewardsReportsStatsYear($year, $verbose=false){
	$query = "INSERT INTO `stewards_reports_stats` (`year`) VALUES ($year)";
	return insertQuery($query, $verbose);
}

function incrementStewardsReportStat($year, $stat, $amount, $verbose=false){
	if(getStewardsReportsStatsYear($year, $verbose) <= 0){
		newStewardsReportsStatsYear($year, $verbose);
	}
	
	$query = "UPDATE `stewards_reports_stats` SET `$stat`=`$stat`+$amount WHERE `year`=$year LIMIT 1";
	return query($query, $verbose);
}

function decrementStewardsReportStat($year, $stat, $amount, $verbose=false){
	$query = "UPDATE `stewards_reports_stats` SET `$stat`=`$stat`-$amount WHERE `year`=$year LIMIT 1";
	return query($query, $verbose);
}

function getStewardsReportsStatsYear($year, $verbose=false){
	$query = "SELECT * FROM `stewards_reports_stats` WHERE `year`=$year LIMIT 1";
	return selectQuery($query, $verbose);
}




/* Search */
function printStewardsReportFormField($name, $options){
	global $month_list;
	
	$prepend 	= ($options['options']['prepend']) ? $options['options']['prepend'] : '';
	$note 		= ($options['note']) ? ' <em>'.$options['note'].'</em>' : '';
	
	switch($options['type']){
	
	// Text inputs
	case 'text' :
		return '<label for="'.$prepend.'sr-'.$name.'">'.ucwords(str_replace('_', ' ', $name)).$note.'</label> <input type="text" name="'.$prepend.''.$name.'" id="'.$prepend.'sr-'.$name.'" value="'.htmlentities($_GET[$name], ENT_QUOTES).'" />';
		
	// Radio inputs
	case 'radio' :
		$temp = '<div class="label">'.ucwords(str_replace('_', ' ', $name)).$note.'</div>';
		foreach($options['options'] as $value=>$label){
			$temp .= '<input type="radio" name="'.$prepend.''.$name.'" id="'.$prepend.'sr-'.$name.'-'.$value.'" class="radio" value="'.$value.'"'.( ($_GET[$name] == $value) ? ' checked="checked"' : '').' /> <label for="'.$prepend.'sr-'.$name.'-'.$value.'" class="normal">'.$label.'</label><br />';
		}
		return $temp;
		
	// Select inputs
	case 'select' :
		$temp = '<label for="'.$prepend.'sr-'.$name.'">'.ucwords(str_replace('_', ' ', $name)).$note.'</label>';
		$temp .= '<select name="'.$prepend.''.$name.( ($options['attr']=='multiple') ? '[]' : '' ).'" id="'.$prepend.'sr-'.$name.'" '.$options['attr'].'>';
		$temp .= '<option value=""></option>';
		foreach($options['options'] as $value=>$label){
			$temp .= '<option value="'.$value.'">'.$label.'</option>';
		}
		$temp .= '</select><br />';
		return $temp;
		
	// Date range inputs
	case 'range' :
		$temp  = '<div class="label">'.ucwords(str_replace('_', ' ', $name)).$note.'</div>';
		$temp .= '<input type="text" name="'.$prepend.''.$name.'_from" id="'.$prepend.'sr-'.$name.'-from" class="datepicker" value="'.htmlentities($_GET[$name.'_from'], ENT_QUOTES).'" />';
		$temp .= ' - ';
		$temp .= '<input type="text" name="'.$prepend.''.$name.'_to" id="'.$prepend.'sr-'.$name.'-to" class="datepicker" value="'.htmlentities($_GET[$name.'_to'], ENT_QUOTES).'" />';
      
    return $temp;
	}
}

// Add a 'prepend' value to an input attribute
function prepareStewardsReportFormField($name, $options){
	if($options['options']['prepend']){
		return $options['options']['prepend'] . $name;
	} else {
		return $name;
	}
}


?>