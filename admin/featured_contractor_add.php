<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
	
	
	// Add Featured Contractor
	if(isset($_POST['submit'])){
		try {
			$required = array('month', 'day', 'year', 'contractor');
			
			$validator = new Validator($required);
			$validator->isInt('month');
			$validator->isInt('day');
			$validator->isInt('year');
			$validator->noFilter('contractor');
			$validator->noFilter('description');
			$validator->isValidImageType('image');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				$date = $clean['year'].'-'.$clean['month'].'-'.$clean['day'];
				
				$result = newFeaturedContractor($clean['contractor'], $clean['description'], $date);
				if($result > 0){
					$success = true;
					
					// Upload Image
					if(!empty($_FILES['image']['name'])){
						$extension = getExtension($_FILES['image']['name']);
						$filename = uniqid(time()).'.'.$extension;
						
						if(move_uploaded_file($_FILES['image']['tmp_name'], $file_paths['contractor_images'].$filename)){
							if(updateFeaturedContractorImage($result, $filename) <= 0){
								$success = false;
								deleteFeaturedContractor($result);
								$alerts->addAlert('The image could not be addded. Please try again.');
							}
						} else {
							$success = false;
							deleteFeaturedContractor($result);
							$alerts->addAlert('The image could not be uploaded. Please try again.');
						}
					}
					
					if($success){
						header("Location: featured_contractor.php?nf");
						exit;
					}
				} else {
					$alerts->addAlert('The featured contractor could not be added.');
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
<title>Admin: Add Featured Contractor | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='featured_contractor'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="featured_contractor.php">Featured Contractor</a></li>
         	<li>Add Featured Contractor</li>
         </ul>
               
      	<h1>Admin: Add Featured Contractor</h1>
         
         <p>Complete the form below to add a new featured contractor.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
         	<p><div class="label"><span class="required">*</span> Date</div>
            	<select name="month" id="month" style="width:auto;">
<?php
			for($i=1; $i<=12; $i++){
?>
					<option value="<?php echo $i; ?>"<?php echo ((!empty($_POST['month']) && $_POST['month']==$i) || (empty($_POST['month']) && $i==date('n'))) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php
			}
?>
               </select> 
               <select name="day" id="day" style="width:auto;">
<?php
			for($j=1; $j<=31; $j++){
?>
					<option value="<?php echo $j; ?>"<?php echo ((!empty($_POST['day']) && $_POST['day']==$j) || (empty($_POST['day']) && $j==date('j'))) ? ' selected="selected"' : ''; ?>><?php echo $j; ?></option>
<?php
			}
?>
               </select> 
               <input type="text" name="year" id="year" value="<?php echo (!empty($_POST['year'])) ? $_POST['year'] : date('Y'); ?>" style="width:80px" maxlength="4" /></p>
               <p><label for="contractor"><span class="required">*</span> Contractor</label> <input type="text" name="contractor" id="contractor" value="<?php echo (!empty($_POST['contractor'])) ? stripslashes($_POST['contractor']) : ''; ?>" /></p>
               <p><label for="image">Image</label> <input type="file" name="image" id="image" /></p>
               <p><label for="description">Description</label> <textarea name="description" id="description" cols="40" rows="10" style="width:400px;"><?php echo (!empty($_POST['description'])) ? stripslashes($_POST['description']) : ''; ?></textarea></p>
               <p class="submit"><input type="submit" name="submit" id="submit" value="Add Featured Contractor" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>