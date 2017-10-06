<?php
	
	define('ACCESS_LEVEL_MEMBER', 2);
	define('ACCESS_LEVEL_CONTRACTOR', 3);
	define('ACCESS_LEVEL_BENEFITS_DEPT', 6);
	define('ACCESS_LEVEL_ADMIN', 9);
	
	define('WORKER_TYPE_JOURNEYMAN', 2);
	define('WORKER_TYPE_APPRENTICE', 3);
	define('WORKER_TYPE_PROBATIONARY', 4);
	
	define('JOB_FUNDING_STATE', 2);
	define('JOB_FUNDING_FEDERAL', 3);
	define('JOB_FUNDING_PRIVATE', 4);
	
	define('PAYMENT_TYPE_EVENT_REGISTRATION', 2);
	define('PAYMENT_TYPE_APPLICATION_FEE', 3);
	
	define('APPRENTICESHIP_APPLICATION_FEE', 35);
	
	define('STEWARDS_REPORT_TYPE_REGULAR', 2);
	define('STEWARDS_REPORT_TYPE_PREENGINEERED_METAL', 3);
	define('STEWARDS_REPORT_TYPE_JOB_TARGETED', 4);
	
	
	$file_paths['contractor_images'] = HOME_ROOT."img/contractors/";
	$file_paths['project_images'] = HOME_ROOT."img/projects/";
	$file_paths['documents'] = HOME_ROOT."files/documents/";
	$file_paths['document_repository'] = HOME_ROOT."files/document_repository/";
	$file_paths['contractor_documents'] = HOME_ROOT."files/contractor_documents/";
	$file_paths['info_links'] = HOME_ROOT."files/info_links/";
	$file_paths['job_pictures'] = HOME_ROOT."files/job_pictures/";
	$file_paths['links'] = HOME_ROOT."files/links/";
	$file_paths['scholarship_applications'] = HOME_ROOT."files/scholarship_applications/";
	$file_paths['apprenticeship_applications'] = HOME_ROOT."files/apprenticeship_applications/";
	$file_paths['stewards_reports_photos'] = HOME_ROOT."img/stewards_reports/";
	$file_paths['officer_images'] = HOME_ROOT."img/officers/";
	$file_paths['political_action_files'] = HOME_ROOT."files/political_action/";
	$file_paths['political_action_images'] = HOME_ROOT."img/political_action/";
	$file_paths['stewards_reports'] = FILES_ROOT."stewards-reports/";
	
	$url_paths['contractor_images'] = WEB_ROOT."img/contractors/";
	$url_paths['project_images'] = WEB_ROOT."img/projects/";
	$url_paths['stewards_reports_photos'] = WEB_ROOT."img/stewards_reports/";
	$url_paths['officer_images'] = WEB_ROOT."img/officers/";
	$url_paths['political_action_images'] = WEB_ROOT."img/political_action/";
	
		
	$state_list = array(
		'AL'=>"Alabama",
		'AK'=>"Alaska",
		'AB'=>"Alberta", 
		'AZ'=>"Arizona", 
		'AR'=>"Arkansas", 
		'BC'=>"British Columbia",
		'CA'=>"California", 
		'CO'=>"Colorado", 
		'CT'=>"Connecticut", 
		'DE'=>"Delaware", 
		'DC'=>"District Of Columbia", 
		'FL'=>"Florida", 
		'GA'=>"Georgia", 
		'HI'=>"Hawaii", 
		'ID'=>"Idaho", 
		'IL'=>"Illinois", 
		'IN'=>"Indiana", 
		'IA'=>"Iowa", 
		'KS'=>"Kansas", 
		'KY'=>"Kentucky", 
		'LA'=>"Louisiana", 
		'ME'=>"Maine", 
		'MB'=>"Manitoba",
		'MD'=>"Maryland", 
		'MA'=>"Massachusetts", 
		'MI'=>"Michigan", 
		'MN'=>"Minnesota", 
		'MS'=>"Mississippi", 
		'MO'=>"Missouri", 
		'MT'=>"Montana",
		'NE'=>"Nebraska",
		'NV'=>"Nevada",
		'NB'=>"New Brunswick",
		'NL'=>"Newfoundland and Labrador",
		'NH'=>"New Hampshire",
		'NJ'=>"New Jersey",
		'NM'=>"New Mexico",
		'NY'=>"New York",
		'NC'=>"North Carolina",
		'ND'=>"North Dakota",
		'NT'=>"Northwest Territories",
		'NS'=>"Nova Scotia",
		'NU'=>"Nunavut",
		'OH'=>"Ohio", 
		'OK'=>"Oklahoma",
		'ON'=>"Ontario", 
		'OR'=>"Oregon", 
		'PA'=>"Pennsylvania",
		'PR'=>"Puerto Rico",
		'PE'=>"Prince Edward Island",
		'QC'=>"Quebec",
		'RI'=>"Rhode Island", 
		'SK'=>"Saskatchewan",
		'SC'=>"South Carolina", 
		'SD'=>"South Dakota",
		'TN'=>"Tennessee", 
		'TX'=>"Texas", 
		'UT'=>"Utah", 
		'VT'=>"Vermont", 
		'VA'=>"Virginia", 
		'WA'=>"Washington", 
		'WV'=>"West Virginia", 
		'WI'=>"Wisconsin", 
		'WY'=>"Wyoming",
		'YT'=>"Yukon Territory"
	);
	
	$month_list = array(
		1=>'January',
		2=>'February',
		3=>'March',
		4=>'April',
		5=>'May',
		6=>'June',
		7=>'July',
		8=>'August',
		9=>'September',
		10=>'October',
		11=>'November',
		12=>'December'
	);

?>