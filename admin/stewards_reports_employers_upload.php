<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	
	// Upload spreadsheet and update paid-through date
	if(isset($_POST['submit'])){
		try {
			$required = array('file');
			
			$validator = new Validator($required);
			$validator->isValidFileType('file', array('csv'));
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				// Loop through all rows
				if(($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
					
					$row = 0;
					$successes_updated = 0;
					$successes_added = 0;
					
					while(($data = fgetcsv($handle, 1000, ",")) !== false){
						if($row <= 0){ $row++; continue; }
						
						$clean = array_map('escapeData', $data);
						
						
						$employer_id = $clean[0];
						$employer_name = $clean[1];
						$paid_through_date = date('Y-m-d', strtotime($clean[2]));
						
						
						// Update employer?
						if(getEmployer($employer_id) > 0){
												
							if(updateEmployerPaidThroughDate($employer_id, $paid_through_date) > 0){
								$successes_updated++;
							}
							
						
						// or add new employer
						} else {
														
							if(newEmployer($employer_id, $employer_name, $paid_through_date) > 0){
								$successes_added++;
							}
							
						}
						
							
						/*
						if(updateEmployerPaidThroughDate($clean[0], date('Y-m-d', strtotime($clean[2]))) > 0){
							$successes++;
						}
						*/
						
						$row++;
					}
					
					$alerts->addAlert($successes_updated.' employers were successfully updated. '.$successes_added.' employers were successfully added.', 'success');
				} else {
					$alerts->addAlert('The file could not be opened.');
				}
				
				
				/*
				// Delete all existing employers
				deleteEmployers();
				
				// Loop through all rows
				if(($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
					
					$row = 0;
					$successes = 0;
					while(($data = fgetcsv($handle, 1000, ",")) !== false){
						if($row <= 0){ $row++; continue; }
						
						$clean = array_map('escapeData', $data);
												
						if(newEmployer($clean[0], ucwords(strtolower($clean[1])), date('Y-m-d', strtotime($clean[2]))) > 0){
							$successes++;
						}
						
						$row++;
					}
					
					$alerts->addAlert($successes.' employers were successfully added.');
				} else {
					$alerts->addAlert('The file could not be opened.');
				}
				*/
				
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
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Employers | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_employers'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">         
      	<h1>Admin: Stewards Reports: Upload Employers CSV File</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <p>This action will update the paid-through date for existing employers, and add new employers not found in the system.</p>
         
         <p>.csv files <strong>must</strong> have the following columns, in order:</p>
         
         <p>ID, Employer Name, Paid-Through Date. A header row is assumed and will be skipped.</p>
         
         <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
            <p><label for="file"><span class="required">*</span> CSV File</label> <input type="file" name="file" id="file" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Upload Spreadsheet" /></p>
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>