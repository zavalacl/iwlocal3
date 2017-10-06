<?php
	require('config.php');
	$access_level=ACCESS_LEVEL_BENEFITS_DEPT; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	$employer_id = (int) $_GET['eid'];
	
	
	// Update an employer
	if(isset($_POST['submit'])){
		try {
			$required = array('name', 'paid_through');
			
			$validator = new Validator($required);
			$validator->noFilter('name');
			$validator->isValidDate('paid_through');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(editEmployer($employer_id, $clean['name'], $clean['paid_through'])){
					$alerts->addAlert('The employer was successfully updated.', 'success');					
				} else {
					$alerts->addAlert('The employer could not be updated.');
				}

			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('There was an unknown error. Please try again.');
		}
	}
	
	
	// Get employer info
	$employer_info = getEmployer($employer_id);
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Benefits Department: Employers | Iron Workers Local Union No. 3 | Western and Central PA</title>
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
      	<?php $page='employers'; include("subnav_benefits.php"); ?>
      </div><!-- div.left -->
      <div class="right">         
      	<h1>Benefits Department: Update an Employer</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <p>Complete the form below to update the employer.</p>
                  
         <form action="<?php echo getSelf(); ?>?eid=<?php echo $employer_id; ?>" method="post">
            <p><div class="label">Employer ID:</div> <?php echo $employer_id; ?></p>
            <p><label for="name"><span class="required">*</span> Employer Name</label> <input type="text" name="name" id="name" value="<?php echo htmlentities($employer_info['name'], ENT_QUOTES); ?>" /></p>
            <p><label for="paid_through"><span class="required">*</span> Paid-Through Date <em>YYYY-MM-DD</em></label> <input type="text" name="paid_through" id="paid_through" class="datepicker" value="<?php echo htmlentities($employer_info['paid_through'], ENT_QUOTES); ?>" /></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Update Employer" /></p>
         </form>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>