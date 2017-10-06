<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/faqs.php');
	
	
	// Add FAQ
	if(isset($_POST['submit'])){
		try {
			$required = array('question', 'answer');
			
			$validator = new Validator($required);
			$validator->noFilter('question');
			$validator->noFilter('answer');
			
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				if(newFaq($clean['question'], $clean['answer']) > 0){
					header("Location: faqs.php?nf");
					exit;
				} else {
					$alerts->addAlert('The FAQ could not be added.');
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
<title>Admin: FAQs | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='faqs'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<ul id="breadcrumbs">
         	<li><a href="faqs.php">FAQs</a></li>
         	<li>Add FAQ</li>
         </ul>
               
      	<h1>Admin: Add FAQ</h1>
         
         <p>Complete the form below to create a new FAQ.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
			<form action="<?php echo getSelf(); ?>" method="post">
         	<p><label for="question"><span class="required">*</span> Question</label> <input type="text" name="question" id="question" value="<?php echo (!empty($_POST['question'])) ? stripslashes($_POST['question']) : ''; ?>" style="width:400px;" maxlength="255" /></p>
            <p><label for="answer"><span class="required">*</span> Answer</label> <textarea name="answer" id="answer" class="wysiwyg"><?php echo (!empty($_POST['answer'])) ? stripslashes($_POST['answer']) : ''; ?></textarea></p>
            <p class="submit"><input type="submit" name="submit" id="submit" value="Add FAQ" /></p>
         </form> 
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>