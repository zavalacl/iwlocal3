<?php
	require_once('config.php');
	
	// Pass id (file ID) and t (file type) as GET URL params
	if(!empty($_GET['id']) && !empty($_GET['t'])){
	
		$file_id = escapeData($_GET['id']);
	
		// Use a switch/case statment to target correct file type
		switch($_GET['t']){
			
		// Document Repository
		case 'repos' :
			require_once('functions/document_repository.php');
			$_GET['t'] = 'document_repository';
			
			$file_info = getRepositoryDocument($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementRepositoryDocumentDownloads($file_id);
			
			break;
			
		// Document Library
		case 'documents' :
			require_once('functions/document_library.php');
			
			$file_info = getDocument($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementDocumentDownloads($file_id);
			
			break;
			
		// Polical Action Files
		case 'political_action_files' :
			require_once('functions/political_action.php');
			
			$file_info = getPoliticalActionFile($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementPoliticalActionFileDownloads($file_id);
			
			break;
			
		// Information Links
		case 'info_links' :
			require_once('functions/announcements.php');
			
			$file_info = getInformationLink($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementInformationLinkDownloads($file_id);
			
			break;
			
		// Job Pictures
		case 'job_pictures' :
			require_once('functions/announcements.php');
			
			$file_info = getJobPictureLink($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementJobPictureLinkDownloads($file_id);
			
			break;
			
		// Other Links
		case 'links' :
			require_once('functions/announcements.php');
			
			$file_info = getLink($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementLinkDownloads($file_id);
			
			break;
			
		// Scholarship Applications
		case 'scholarship_applications' :
			require("authenticate.php");
			require_once('functions/scholarship_applications.php');
			
			$file_info = getScholarshipApplication($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementScholarshipApplicationDownloads($file_id);
			
			break;
			
		// Contractor Documents
		case 'contractor_documents' :
			require_once('functions/contractors.php');
			
			$file_info = getContractorDocument($file_id);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementContractorDocumentDownloads($file_id);
			
			break;
			
		// Apprenticeship Applications
		case 'apprenticeship_applications' :
			$require_admin=true; require("authenticate.php");
			require_once('functions/apprenticeships.php');
			
			$hash = escapeData($_GET['h']);
			
			$file_info = getApprenticeshipApplication($file_id, $hash);
			if($file_info <= 0) die('The requested download could not be found. It may have expired.');
				
			$name = $file_info['original_filename'];
			$filename = $file_info['filename'];
			
			incrementApprenticeshipApplicationDownloads($file_id);
			
			break;
			
		// Steward's Reports
		case 'stewards_reports' :
			$access_level = array(ACCESS_LEVEL_MEMBER, ACCESS_LEVEL_BENEFITS_DEPT); require("authenticate.php");
			require_once('functions/stewards_reports.php');
			
			$report_info = getStewardsReport($file_id);
			
			$name = str_replace(' ', '_', $report_info['project_name']).'_'.date('Y-n-j', strtotime($report_info['date_submitted'])).'.pdf';
			
			// Include accidents? (admin only)
			if(isset($_GET['accidents']) && $_SESSION['user_info']['access_level'] === ACCESS_LEVEL_ADMIN){
				$accidents = true;
			} else {
				$accidents = false;
			}
			
			// Use the new version?
			if($report_info['version']==2){
				$filename = createStewardsReportPDFV2($file_id, $accidents);
			} else {
				$filename = createStewardsReportPDF($file_id, $accidents);
			}
						
			break;
			
		default :
			die('Invalid request');
		}
		
		$extension = getExtension($filename);
		
		if(file_exists($file_paths[$_GET['t']].$filename)){
		
			// Force download, renaming the file back to the original (if applicable)
			header('Content-Description: File Transfer');
			
			switch($extension) {
			case "pdf":
				header("Content-type: application/pdf");
				break;
				
			case "xls":
				header("Content-type: application/vnd.ms-excel");
				break;
				
			case "doc":
				header("Content-type: application/application/msword");
				break;
			
			default;
				header('Content-Type: application/octet-stream');
				break;
			}
			
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file_paths[$_GET['t']].$filename));
			ob_clean();
			flush();
			readfile($file_paths[$_GET['t']].$filename); 	
			exit();
		
		} else {
			header("HTTP/1.0 404 Not Found");
			include(HOME_ROOT.'404.php');
			exit;
		}
	}
