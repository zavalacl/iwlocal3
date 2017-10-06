<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
	
	
	// Contractor ID
	$contractor_id = (int) $_GET['cid'];
	
	
	// Edit a contractor
	if(isset($_POST['submit'])){
		try {
			$required = array('name');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->noFilter('address');
			$validator->noFilter('address_2');
			$validator->noFilter('city');
			$validator->noFilter('state');
			$validator->noFilter('zip');
			$validator->noFilter('phone');
			$validator->noFilter('fax');
			$validator->noFilter('url');
			$validator->noFilter('specialties');
			$validator->noFilter('categories', true);
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if( editContractor($contractor_id, $clean['name'], $clean['address'], $clean['address_2'], $clean['city'], $clean['state'], $clean['zip'], cleanPhone($clean['phone']), cleanPhone($clean['fax']), $clean['url'], $clean['specialties']) ){
					$alerts->addAlert('The contractor was successfully updated.', 'success');
					
					
					// Categories
					deleteContractorCategoryMapByContractor($contractor_id); // Clean slate
					
					if($filtered['categories']){
						
						$successes = 0;
						foreach($filtered['categories'] as $category_id){
							if(newContractorCategoryMap($contractor_id, $category_id) > 0){
								$successes++;
							}
						}
					}
					
					
				} else {
					$alerts->addAlert('The contractor could not be updated.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Contractor info
	$contractor_info = getContractor($contractor_id);
	$contractors_categories = getContractorCategoryMapByContractor($contractor_id, true);
	
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Contractors | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
         	<li>Edit Contractor</li>
         </ul>
               
      	<h1>Admin: Edit Contractor</h1>
         
         <p>Complete the form below to update the contractor.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>?cid=<?php echo $contractor_id; ?>" method="post">
					 
					  <div style="width: 60%">
					 
	            <p><label for="name"><span class="required">*</span> Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($contractor_info['name'], ENT_QUOTES); ?>" /></p>
	            
	            <p>
		            <label for="address"><span class="required">*</span> Address</label> 
		            <input type="text" name="address" id="address" value="<?php echo htmlentities($contractor_info['address'], ENT_QUOTES); ?>" style="margin-bottom: 4px;" /> <br />
		            <input type="text" name="address_2" id="address_2" value="<?php echo htmlentities($contractor_info['address_2'], ENT_QUOTES); ?>" />
	
		           </p>
		           
		           <p>
		            <label for="city"><span class="required">*</span> City</label> 
		            <input type="text" name="city" id="city" value="<?php echo htmlentities($contractor_info['city'], ENT_QUOTES); ?>" />
		           </p>
		           
		           <p>
		            <label for="state"><span class="required">*</span> State</label> 
		            <select name="state" id="state">
			            <option value=""></option>
<?php
						foreach($state_list as $abbrev => $state){
?>
									<option value="<?php echo $abbrev; ?>"<?php echo ($contractor_info['state'] == $abbrev) ? ' selected="selected"' : ''; ?>><?php echo $state; ?></option>
<?php
						}
?>
		            </select>
		           </p>
		           
		           <p>
		            <label for="zip"><span class="required">*</span> Zip</label> 
		            <input type="text" name="zip" id="zip" value="<?php echo htmlentities($contractor_info['zip'], ENT_QUOTES); ?>" />
		           </p>
		           
		           <p>
		            <label for="phone"><span class="required">*</span> Phone</label> 
		            <input type="text" name="phone" id="phone" value="<?php echo formatPhone(htmlentities($contractor_info['phone'], ENT_QUOTES)); ?>" />
		           </p>
		           
		           <p>
		            <label for="fax">Fax</label> 
		            <input type="text" name="fax" id="fax" value="<?php echo formatPhone(htmlentities($contractor_info['fax'], ENT_QUOTES)); ?>" />
		           </p>
		           
		           <p>
		            <label for="url">Web Address/URL</label> 
		            <input type="text" name="url" id="url" value="<?php echo htmlentities($contractor_info['url'], ENT_QUOTES); ?>" />
		           </p>
		           
		           <p>
		             <label for="specialties">Specialties/Certifications</label> 
		             <textarea name="specialties" id="specialties" cols="40" rows="10" style="width: 400px;"><?php echo htmlentities($contractor_info['specialties']); ?></textarea>
		           </p>
	            
							 <p class="submit"><input type="submit" name="submit" id="submit" value="Update Contractor" /></p>
				 		</div>
						
						 <div style="width: 40%">
						 		<label for="categories">Categories <i>Select multiple</i></label> 
		            <select name="categories[]" id="categories" multiple="multiple" style="height: 620px;">
			            <option value=""></option>
<?php
						$categories = getContractorCategories();
						if($categories > 0){
							foreach($categories as $category){
?>
									<option value="<?php echo $category['category_id']; ?>"<?php echo ( @in_array($category['category_id'], $contractors_categories)) ? ' selected="selected"' : ''; ?>><?php echo $category['category']; ?></option>
<?php
							}
						}
?>
		            </select>
					 </div> 
						
						
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>