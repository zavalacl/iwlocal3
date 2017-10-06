<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require('functions/stewards_reports.php');
	
	// New employer?
	if(isset($_GET['ne'])) $alerts->addAlert('The employer was successfully added.', 'success');
	
	// Delete employer
	if(!empty($_GET['d1'])){
		$employer_id = (int) $_GET['d1'];
		
		if(deleteEmployer($employer_id) > 0){
			$alerts->addAlert('The employer was successfully deleted.', 'success');
		} else {
			$alerts->addAlert('The employer could not be deleted.');
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
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_reports_employers'; include("subnav_admin.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<a href="stewards_reports_employers_add.php" class="icon_add">Add an Employer</a><br />
      	<a href="stewards_reports_employers_upload.php" class="icon_add">Update Paid-Through date via CSV file upload</a>
         
      	<h1>Admin: Stewards Reports: Employers</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <table>
        		<tr>
            	<th>ID</th>
              <th>Employer Name</th>
              <th>Paid Through</th>
              <th width="36">&nbsp;</th>
            </tr>
<?php
		$employers = getEmployers();
		if($employers > 0){
			foreach($employers as $employer){
				$class = ($class=='odd') ? 'even' : 'odd';
?>
            <tr class="<?php echo $class; ?>">
            	<td><?php echo $employer['employer_id']; ?></td>
              <td><?php echo $employer['name']; ?></td>
              <td><?php echo $employer['paid_through']; ?></td>
              <td>
             		<a href="stewards_reports_employers_edit.php?eid=<?php echo $employer['employer_id']; ?>" class="icon_edit">Edit</a>
              	<a href="javascript:confirm_deletion(1,<?php echo $employer['employer_id']; ?>,'<?php echo getSelf(); ?>','employer',0);" class="icon_delete">Delete</a>
              </td>
            </tr>
<?php
			}
		} else {
?>
						<tr>
            	<td colspan="4">There are currently no employers.</td>
            </tr>
<?php
		}
?>
         </table>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>