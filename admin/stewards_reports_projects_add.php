<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	
	// Add project
	if(isset($_POST['submit'])){
		try {
			$required = array('name', 'address', 'city', 'county');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->noFilter('address');
			$validator->noFilter('city');
			$validator->noFilter('county');
			$validator->isInt('funding');

			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				// Add projects
				$project_id = newProject($clean['name'], $clean['address'], $clean['city'], $clean['county'], $clean['funding']);
				if($project_id > 0){
				
					header('Location: stewards_reports_projects.php?np');
					exit;
					
				} else {
					$alerts->addAlert('The project could not be added.');
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
<title>Admin: Stewards Reports: Projects | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_projects'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="stewards_reports_projects.php">Projects</a></li>
         	<li>Add Project</li>
         </ul>
         
      	<h1>Admin: Stewards Reports: Add Project</h1>
         
        <?php if($alerts->hasAlerts()) echo $alerts; ?>
        
        <p>Use the form below to add a new project for Steward's Reports.</p>
         
				<form action="<?php echo getSelf(); ?>" method="post" enctype="multipart/form-data">
         	<p><label for="name"><span class="required">*</span> Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($_POST['name'], ENT_QUOTES); ?>" maxlength="255" /></p>
         	
         		         	
       		<p><label for="address"><span class="required">*</span> Address</label> <input type="text" name="address" id="address" value="<?php echo htmlentities($_POST['address'], ENT_QUOTES); ?>" maxlength="255" /></p>
         	<p><label for="city"><span class="required">*</span> City</label> <input type="text" name="city" id="city" value="<?php echo htmlentities($_POST['city'], ENT_QUOTES); ?>" maxlength="128" /></p>
         	<p>
         		<label for="county"><span class="required">*</span> County</label> 
         		<select name="county" id="county">
         			<option value=""></option>
<?php
			$counties = getProjectCounties();
			if($counties > 0){
				foreach($counties as $county){
?>
							<option value="<?php echo $county['county']; ?>"<?php echo ($_POST['county']==$county['county']) ? ' selected="selected"' : ''; ?>><?php echo $county['county']; ?></option>
<?php
				}
			}
?>
         		</select>
         	</p>
         	
         	<p>
         		<div class="label">Job Funding</div> 
						<input type="radio" name="funding" id="funding_state" value="<?php echo JOB_FUNDING_STATE; ?>"<?php echo ($_POST['funding']==JOB_FUNDING_STATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="funding_state" class="plain">State</label>
						<input type="radio" name="funding" id="funding_federal" value="<?php echo JOB_FUNDING_FEDERAL; ?>"<?php echo ($_POST['funding']==JOB_FUNDING_FEDERAL) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="funding_federal" class="plain">Federal</label>
						<input type="radio" name="funding" id="funding_private" value="<?php echo JOB_FUNDING_PRIVATE; ?>"<?php echo ($_POST['funding']==JOB_FUNDING_PRIVATE) ? ' checked="checked"' : ''; ?> class="radio" /> <label for="funding_private" class="plain">Private</label>
         	</p>
          <p class="submit"><input type="submit" name="submit" id="submit" value="Add Project" /></p>
        </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>