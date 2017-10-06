<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	$contractor_id = (int) $_GET['cid'];
	
	
	// Edit contractor
	if(isset($_POST['submit'])){
		try {
			$required = array('name');
			
			$validator = new Validator($required);
			$validator->noFilter('name');

			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
								
				if(editGeneralContractor($contractor_id, $clean['name'])){
					$alerts->addAlert('The contractor was successfully updated.', 'success');
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
	
	
	// Get contractor info
	$contractor_info = getGeneralContractor($contractor_id);
	
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
         
      	<h1>Admin: Stewards Reports: Edit Contractor</h1>
         
        <?php if($alerts->hasAlerts()) echo $alerts; ?>
        
        <p>Use the form below to update a contractor for Steward's Reports.</p>
         
				<form action="<?php echo getSelf(); ?>?cid=<?php echo $contractor_id; ?>" method="post">
         	<p><label for="name"><span class="required">*</span> Contractor Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($contractor_info['name'], ENT_QUOTES); ?>" maxlength="255" /></p>
          <p class="submit"><input type="submit" name="submit" id="submit" value="Update Contractor" /></p>
        </form>
                  
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>