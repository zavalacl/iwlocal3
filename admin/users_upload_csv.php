<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require_once('functions/users.php');
	
	
	// Upload .csv File
	if(isset($_POST['submit'])){
		
		if(!empty($_FILES['file']['name'])){
			$extension = strtolower(getExtension($_FILES['file']['name']));
			if($extension === 'csv'){
				
				// Counters
				$inserted = 0;
				$updated = 0;
				
				// Read text file line by line
				$handle = fopen($_FILES['file']['tmp_name'], "r") or exit("Unable to open file!");
				while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
										
					// Make sure the expected number of columns exist
					if(count($data) != 6){
						break;
						$alerts->addAlert('The file could not be processed because it was improperly formatted.');
					}
					
					$fields['username'] = (int) $data[0];
					$fields['first_name'] = ucwords(strtolower($data[1]));
					$fields['last_name'] = ucwords(strtolower($data[2]));
					
					switch($data[3]){
					case 'A' :
						$fields['class'] = WORKER_TYPE_APPRENTICE;
						break;
					case 'J' :
						$fields['class'] = WORKER_TYPE_JOURNEYMAN;
						break;
					case 'R' :
						$fields['class'] = WORKER_TYPE_PROBATIONARY;
						break;
					default :
						$fields['class'] = false;
						break;
					}
					
					$fields['month_dues_paid'] = date('Y-m-d', strtotime($data[4]));
					$fields['local_number'] = (int) $data[5];
					
					$clean = array_map('escapeData', $fields);
															
				
					// Find user by book number
					$user_info = getUserByUsername($clean['username']);
					
					// If username (book number) does NOT already exists, then ADD user.
					if($user_info <= 0){
					
						//***************** NOT adding users at this time *****************//
						
						/*$result = createUser($clean['first_name'], $clean['last_name'], $clean['username'], $clean['password'], $clean['local_number'], $clean['class'], $clean['month_dues_paid']);
						if($result > 0){
							$inserted++;
							updateUserAccessLevel($result, ACCESS_LEVEL_MEMBER);
							updateUserStatus($result, 1);
						} else {
							// The user account could not be added.
						}*/
						
						
					// Otherwise, EDIT user's information	
					} else {
						if(query("UPDATE `users` SET `first_name`='{$clean['first_name']}', `last_name`='{$clean['last_name']}', `class`='{$clean['class']}', `month_dues_paid`='{$clean['month_dues_paid']}', `local_number`='{$clean['local_number']}' WHERE `user_id`='{$user_info['user_id']}' LIMIT 1") > 0){
							$updated++;
						}
					}
				}
				fclose($handle);
				
				$alerts->addAlert(number_format($updated).' users were updated.', 'success');
				
			} else {
				$alerts->addAlert('A file with a ".csv" extension is expected. Please try again.');
			}
		} else {
			$alerts->addAlert('Please select a .csv file to upload.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Upload Users .csv File | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='users'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="users.php">User Accounts</a></li>
         	<li>Upload Users .csv File</li>
         </ul>
               
      	<h1>Admin: Upload Users .csv File</h1>
         
         <p>Use the form below the upload a .csv file to update the users database (users will not be added or deleted).</p>
         
         <p>.csv files <strong>must</strong> have the following columns, in order:</p>
         
         <p>Member Number, First Name, Last Name, Class, Paid-Through Date, Local #</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
            <p><label for="files"><span class="required">*</span>.csv File</label> <input type="file" name="file" id="file" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Upload File" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>