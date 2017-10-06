<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/officers.php');
	
	
	// Officer ID
	$officer_id = (int) $_GET['oid'];
	
	
	// Edit officer
	if(isset($_POST['submit'])){
		try {
			$required = array('category', 'first_name', 'last_name');
			
			$validator = new Validator($required);
			$validator->isInt('category');
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('title');
			$validator->isValidImageType('image');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editOfficer($officer_id, $clean['category'], $clean['first_name'], $clean['last_name'], $clean['title'])){
					$alerts->addAlert('The officer was successfully updated.', 'success');
					
					// Upload Image?
					if(!empty($_FILES['image']['name'])){
						$extension = getExtension($_FILES['image']['name']);
						$filename = uniqid(time()).'.'.$extension;
						
						if(move_uploaded_file($_FILES['image']['tmp_name'], $file_paths['officer_images'].$filename)){
							if(updateOfficerImage($officer_id, $filename) <= 0){
								unlink($file_paths['officer_images'].$filename);
								$alerts->addAlert('The image could not be saved.');
							}
						} else {
							$alerts->addAlert('The image could not be uploaded.');
						}
					}
				} else {
					$alerts->addAlert('The officer could not be updated.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Officer info
	$officer_info = getOfficer($officer_id);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Edit Officer | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='officers'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="officers.php">Officers</a></li>
         	<li>Edit Officer</li>
         </ul>
               
      	<h1>Admin: Edit Officer</h1>
         
         <p>Complete the form below to add a new officer.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>?oid=<?php echo $officer_id; ?>" method="post" enctype="multipart/form-data">
					 	<img src="<?php echo new Image($officer_info['image'],200,300,'fit','officer_images'); ?>" alt="" style="float: right;">
					 	
				 		<p>
				 			<label for="category"><span class="required">*</span> Category</label>
				 			<select name="category" id="category">
				 				<option value=""></option>
<?php
					$categories = getOfficerCategories();
					if($categories > 0){
						foreach($categories as $category){
?>
								<option value="<?php echo $category['category_id']; ?>"<?php echo ($officer_info ['category_id'] == $category['category_id']) ? ' selected="selected"' : ''; ?>><?php echo $category['category']; ?></option>
<?php
						}
					}
?>
				 			</select>
				 		</p>
            <p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($officer_info ['first_name'], ENT_QUOTES); ?>" /></p>
						<p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($officer_info ['last_name'], ENT_QUOTES); ?>" /></p>
						<p><label for="title">Title</label> <input type="text" name="title" id="title" value="<?php echo htmlentities($officer_info ['title'], ENT_QUOTES); ?>" /></p>
						<p>
            	<label for="image">Replace Image</label> 
              <input type="file" name="image" id="image" style="width:auto;" /> 
            </p>

            <p class="submit"><input type="submit" name="submit" id="submit" value="Update Officer" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>