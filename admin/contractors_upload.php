<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require_once('functions/contractors.php');
	

	// Upload .csv File
	if(isset($_POST['submit'])){
		
		if(!empty($_POST['category'])){
			$category_id = (int) $_POST['category'];
			
		
			if(!empty($_FILES['file']['name'])){
				$extension = strtolower(getExtension($_FILES['file']['name']));
				if($extension === 'csv'){
					
					// Counters
					$successes_inserted = 0;
					$successes_updated = 0;
					
					// Read text file line by line
					$handle = fopen($_FILES['file']['tmp_name'], "r") or exit("Unable to open file!");
					while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
											
						// Make sure the expected number of columns exist
						if(count($data) > 8){
							break;
							$alerts->addAlert('The file could not be processed because it was improperly formatted.');
						}
						
						$fields['name'] 			= $data[0];
						$fields['address'] 		= $data[1];
						$fields['address_2'] 	= $data[2];
						$fields['city'] 			= $data[3];
						$fields['state'] 			= $data[4];
						$fields['zip'] 				= $data[5];
						$fields['phone'] 			= cleanPhone($data[6]);
						$fields['fax'] 				= '';
						$fields['url'] 				= $data[7];
						$fields['specialties']= $data[8];
	
	
						$clean = array_map('escapeData', $fields);
						
						
						// Try to find existing contractor
						$contractor_info = getContractorByName($clean['name']);
						if($contractor_info > 0){
							
							// Update existing contractor
							if( editContractor($contractor_info['contractor_id'], $clean['name'], $clean['address'], $clean['address_2'], $clean['city'], $clean['state'], $clean['zip'], $clean['phone'], $clean['fax'], $clean['url'], $clean['specialties']) ){
								$successes_updated++;
								
								if( ! contractorIsMappedToCategory($contractor_info['contractor_id'], $category_id)){
									newContractorCategoryMap($contractor_info['contractor_id'], $category_id);
								}
							}
							
						} else {
							
							// Add new contractor
							$contractor_id = newContractor($clean['name'], $clean['address'], $clean['address_2'], $clean['city'], $clean['state'], $clean['zip'], $clean['phone'], $clean['fax'], $clean['url'], $clean['specialties']);
							if($contractor_id > 0){
								$successes_inserted++;
								
								newContractorCategoryMap($contractor_id, $category_id);
							}
						}
						
					}
					
					fclose($handle);
					
					$alerts->addAlert(number_format($successes_inserted).' contractors were added.', 'success');
					$alerts->addAlert(number_format($successes_updated).' contractors were updated.', 'success');
					
				} else {
					$alerts->addAlert('A file with a ".csv" extension is expected. Please try again.');
				}
			} else {
				$alerts->addAlert('Please select a .csv file to upload.');
			}
		} else {
			$alerts->addAlert('Please select a contractor category.');
		}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Upload Contractors .csv File | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='contractors'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="contractors.php">Contractors</a></li>
         	<li>Upload Contractors .csv File</li>
         </ul>
               
      	<h1>Admin: Upload Contractors .csv File</h1>
         
         <p>Use the form below the upload a .csv file to update the contractors database. If a contractor with the same name is found their information will be updated. Otherwise they will be added.</p>
         
         <p>.csv files <strong>must</strong> have <strong>NO</strong> header row and the following columns, in order:</p>
         
         <p>Name, Address, Address 2, City, State, Zip Code, Phone, Web Address/URL, Specialties</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
					 <p>
						 <label for="category"><span class="required">*</span>Category</label> 
             <select name="category" id="category" required>
	            <option value=""></option>
<?php
						$categories = getContractorCategories();
						if($categories > 0){
							foreach($categories as $category){
?>
								<option value="<?php echo $category['category_id']; ?>"<?php echo ($category['category_id'] == $_POST['category']) ? ' selected="selected"' : ''; ?>><?php echo $category['category']; ?></option>
<?php
							}
						}
?>
		          </select>
					 </p>
					 
           <p><label for="files"><span class="required">*</span>.csv File</label> <input type="file" name="file" id="file" required /></p>
           
           <p class="submit"><input type="submit" name="submit" id="submit" value="Upload File" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>