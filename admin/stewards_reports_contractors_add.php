<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	
	// Upload spreadsheet and replace all existing contractors
	if(isset($_POST['submit_upload'])){
		try {
			$required = array('file');
			
			$validator = new Validator($required);
			$validator->isValidFileType('file', array('csv'));
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				
				// Delete all existing contractors
				deleteGeneralContractors();
				
				// Loop through all rows
				if(($handle = fopen($_FILES['file']['tmp_name'], "r")) !== false) {
					
					$row = 0;
					$successes = 0;
					while(($data = fgetcsv($handle, 1000, ",")) !== false){
						if($row <= 0){ $row++; continue; }
						
						$clean = array_map('escapeData', $data);
												
						if(newGeneralContractor(ucwords(strtolower($clean[0]))) > 0){
							$successes++;
						}
						
						$row++;
					}
					
					$alerts->addAlert($successes.' contractors were successfully added.');
				} else {
					$alerts->addAlert('The file could not be opened.');
				}				
				
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again.');
		}
	}
	
	
	// Add an individual contractor
	if(isset($_POST['submit'])){
		try {
			$required = array('name');
			
			$validator = new Validator($required);
			$validator->noFilter('name');

			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				$contractor_id = newGeneralContractor($clean['name']);
				if($contractor_id > 0){
				
					header('Location: stewards_reports_contractors.php?nc');
					exit;
					
				} else {
					$alerts->addAlert('The contractor could not be added.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Stewards Reports: Contractors | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_contractors'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="stewards_reports_contractors.php">Contractors</a></li>
         	<li>Add Contractor</li>
         </ul>
         
      	<h1>Admin: Stewards Reports: Add Contractor</h1>
         
        <?php if($alerts->hasAlerts()) echo $alerts; ?>
        
        <p>Use the form below to add a single new contractor for Steward's Reports.</p>
         
				<form action="<?php echo getSelf(); ?>" method="post">
         	<p><label for="name"><span class="required">*</span> Contractor Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($_POST['name'], ENT_QUOTES); ?>" maxlength="255" /></p>
          <p class="submit"><input type="submit" name="submit" id="submit" value="Add Contractor" /></p>
        </form>
        
        <hr />
        
        <h2>OR Upload a CSV File</h2>
        
        <p>This action will clear the database of all existing general contractors.</p>
        
        <p>.csv files <strong>must</strong> have only one column containing the name of the contractor. A header row is assumed and will be skipped.</p>
         
	      <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
	         <p><label for="file"><span class="required">*</span> CSV File</label> <input type="file" name="file" id="file" /></p>
	         <p class="submit"><input type="submit" name="submit_upload" id="submit_upload" value="Upload Spreadsheet" /></p>
	      </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>