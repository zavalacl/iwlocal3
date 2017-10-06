<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	
	// Add an employer
	if(isset($_POST['submit'])){
		try {
			$required = array('employer_id', 'name', 'paid_through');
			
			$validator = new Validator($required);
			$validator->isInt('employer_id');
			$validator->noFilter('name');
			$validator->isValidDate('paid_through');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
			
				// Make sure that the ID is not already taken
				if(getEmployer($clean['employer_id']) <= 0){
				
					// Add employer
					$employer_id = newEmployer($clean['employer_id'], $clean['name'], $clean['paid_through']);
					if($employer_id > 0){
						
						header('Location: stewards_reports_employers.php?ne');
						exit;
						
					} else {
						$alerts->addAlert('The employer could not be added.');
					}
				} else {
					$alerts->addAlert('The employer ID you entered is already in use.');
				}
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
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" />

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript">
$(function(){

	// Datepicker
	$(".datepicker").datepicker({
			showOn: "both",
			buttonImage: "/img/calendar.gif",
			dateFormat: 'yy-mm-dd'
	});
});
</script>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_employers'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">         
      	<h1>Admin: Stewards Reports: Add an Employer</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <p>Complete the form below to add a new employer.</p>
                  
         <form action="<?php echo getSelf(); ?>" method="post">
            <p><label for="employer_id"><span class="required">*</span> Employer ID</label> <input type="text" name="employer_id" id="employer_id" value="<?php echo htmlentities($_POST['employer_id'], ENT_QUOTES); ?>" /></p>
            <p><label for="name"><span class="required">*</span> Employer Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($_POST['name'], ENT_QUOTES); ?>" /></p>
            <p><label for="paid_through"><span class="required">*</span> Paid-Through Date <em>YYYY-MM-DD</em></label> <input type="text" name="paid_through" id="paid_through" class="datepicker" value="<?php echo htmlentities($_POST['paid_through'], ENT_QUOTES); ?>" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Add Employer" /></p>
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>