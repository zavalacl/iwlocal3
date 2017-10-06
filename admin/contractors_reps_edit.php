<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/contractors.php');
	
	
	// Rep ID
	$rep_id = (int) $_GET['rid'];
	
	
	// Edit rep
	if(isset($_POST['submit'])){
		try {
			$required = array('territory', 'first_name', 'last_name');
			
			$validator = new Validator($required);
			$validator->isInt('territory');
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->noFilter('title');
			$validator->noFilter('office');
			$validator->noFilter('fax');
			$validator->noFilter('cell');
			$validator->isEmail('email');
			$validator->noFilter('note');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editContractorRep($rep_id, $clean['territory'], $clean['first_name'], $clean['last_name'], $clean['title'], cleanPhone($clean['office']), cleanPhone($clean['fax']), cleanPhone($clean['cell']), $clean['email'], $clean['note'])){
					$alerts->addAlert('The rep was successfully updated.', 'success');

				} else {
					$alerts->addAlert('The rep could not be updated.');
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occurred.');
		}
	}
	
	
	// Rep info
	$rep_info = getContractorRep($rep_id);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: Add Contractor Rep | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='contractors_reps'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="contractors_reps.php">Contractor Reps</a></li>
         	<li>Edit Contractor Rep</li>
         </ul>
               
      	<h1>Admin: Edit Contractor Rep</h1>
                  
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
				 <form action="<?php echo getSelf(); ?>?rid=<?php echo $rep_id; ?>" method="post">
				 		<p>
				 			<label for="territory"><span class="required">*</span> Territory</label>
				 			<select name="territory" id="territory">
				 				<option value=""></option>
<?php
					$territories = getContractorRepTerritories();
					if($territories > 0){
						foreach($territories as $territory){
?>
								<option value="<?php echo $territory['territory_id']; ?>"<?php echo ($rep_info['territory_id'] == $territory['territory_id']) ? ' selected="selected"' : ''; ?>><?php echo $territory['territory']; ?></option>
<?php
						}
					}
?>
				 			</select>
				 		</p>
            <p><label for="first_name"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($rep_info['first_name'], ENT_QUOTES); ?>" /></p>
						<p><label for="last_name"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($rep_info['last_name'], ENT_QUOTES); ?>" /></p>
						<p><label for="title">Title</label> <input type="text" name="title" id="title" value="<?php echo htmlentities($rep_info['title'], ENT_QUOTES); ?>" /></p>

						<p><label for="office">Office</label> <input type="text" name="office" id="office" value="<?php echo htmlentities($rep_info['office'], ENT_QUOTES); ?>" /></p>
						<p><label for="fax">Fax</label> <input type="text" name="fax" id="fax" value="<?php echo htmlentities($rep_info['fax'], ENT_QUOTES); ?>" /></p>
						<p><label for="cell">Cell</label> <input type="text" name="cell" id="cell" value="<?php echo htmlentities($rep_info['cell'], ENT_QUOTES); ?>" /></p>
						<p><label for="email">Email</label> <input type="text" name="email" id="email" value="<?php echo htmlentities($rep_info['email'], ENT_QUOTES); ?>" /></p>
						<p><label for="note">Note</label> <input type="text" name="note" id="note" value="<?php echo htmlentities($rep_info['note'], ENT_QUOTES); ?>" /></p>

            <p class="submit"><input type="submit" name="submit" id="submit" value="Update Contractor Rep" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>