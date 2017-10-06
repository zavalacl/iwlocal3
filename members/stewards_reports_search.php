<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/stewards_reports.php');
		
	// Search
	if(isset($_POST['submit'])){
		try {
			$required = array('search_term', 'search_by', 'date_range');
			
			if($_POST['date_range']=='specific'){
				$required[] = 'from_month';
				$required[] = 'from_day';
				$required[] = 'from_year';
				$required[] = 'to_month';
				$required[] = 'to_day';
				$required[] = 'to_year';
			}
			
			$validator = new Validator($required);
			$validator->noFilter('search_term');
			$validator->noFilter('search_by');
			$validator->noFilter('date_range');
			
			if($_POST['date_range']=='specific'){
				$validator->isInt('from_month', 1, 12);
				$validator->isInt('from_day', 1, 31);
				$validator->isInt('from_year');
				$validator->isInt('to_month', 1, 12);
				$validator->isInt('to_day', 1, 31);
				$validator->isInt('to_year');
			}
				
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				$clean = array_map('escapeData', $filtered);
				
				// Date Range
				if($filtered['date_range']=='specific'){
					$date_from = $clean['from_year'].'-'.$clean['from_month'].'-'.$clean['from_day'];
					$date_to = $clean['to_year'].'-'.$clean['to_month'].'-'.$clean['to_day'];
				}
				
				// Selects
				$selects = "`stewards_reports`.`report_id`, `stewards_reports`.`project_name`, `stewards_reports`.`date_submitted`, `stewards_reports`.`general_contractor`, `stewards_reports`.`company`, `stewards_reports`.`version`";
				
				// Search By
				switch($filtered['search_by']){
				case 'book_number' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects  FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND `stewards_reports_workers`.`book_number`='{$clean['search_term']}' $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC";
					break;
				
				case 'county' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND `stewards_reports`.`project_county` LIKE '%{$clean['search_term']}%' $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC";
					break;
				
				case 'project_name' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND `stewards_reports`.`project_name` LIKE '%{$clean['search_term']}%' $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC";
					break;
					
				case 'general_contractor' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND `stewards_reports`.`general_contractor` LIKE '%{$clean['search_term']}%' $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC";
					break;
					
				case 'company' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND `stewards_reports`.`company` LIKE '%{$clean['search_term']}%' $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC";
					break;
					
				case 'job_description' :
					$date_range = ($filtered['date_range']=='specific') ? "AND (DATE(`stewards_reports`.`date_submitted`)>='$date_from' && DATE(`stewards_reports`.`date_submitted`)<='$date_to')" : "";
					
					$query = "SELECT $selects, MATCH(`job_description`) AGAINST('{$clean['search_term']}*' IN BOOLEAN MODE) AS `score` FROM `stewards_reports`, `stewards_reports_workers` WHERE `stewards_reports`.`report_id`=`stewards_reports_workers`.`report_id` AND MATCH(`job_description`) AGAINST('{$clean['search_term']}*' IN BOOLEAN MODE) $date_range GROUP BY `stewards_reports`.`report_id` ORDER BY `stewards_reports`.`date_submitted` DESC, `score` ASC";
					break;
				
				default :
					$alerts->addAlert('Invalid "Search By" value.');
					break;
				}
				
				
				// Perform Query
				$results = selectArrayQuery($query);
				
				
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
<title>Steward's Reports | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
<style type="text/css">
#date_ranges {
	width:100%;
	padding-top:5px;
}
#date_ranges select, #date_ranges input {
	width:auto;
	margin-bottom:3px;
	font-size:.9em;
}
</style>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='stewards_report_search'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      
      	<?php /*<a href="../get_excel.php?stewards_reports" class="icon_excel">Download Spreadsheet</a>*/ ?>
         
      	<h1>Steward's Reports: Search</h1>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>" method="post">
         	<div class="p"><label for="search_term">Search Term</label> <input type="text" name="search_term" id="search_term" value="<?php echo htmlentities($_POST['search_term'], ENT_QUOTES, 'utf-8'); ?>" /></div>
            <div class="p">
            	<div class="label">Search By</div>
               <input type="radio" name="search_by" id="search_by_book_number" value="book_number"<?php echo (empty($_POST['search_by']) || $_POST['search_by']=='book_number') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_book_number" class="plain">Book Number</label> 
               <input type="radio" name="search_by" id="search_by_county" value="county"<?php echo ($_POST['search_by']=='county') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_county" class="plain">County</label> 
               <input type="radio" name="search_by" id="search_by_project_name" value="project_name"<?php echo ($_POST['search_by']=='project_name') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_project_name" class="plain">Project Name</label>
               <input type="radio" name="search_by" id="search_by_general_contractor" value="general_contractor"<?php echo ($_POST['search_by']=='general_contractor') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_general_contractor" class="plain">General Contractor</label>
               <input type="radio" name="search_by" id="search_by_company" value="company"<?php echo ($_POST['search_by']=='company') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_company" class="plain">Company</label> 
               <input type="radio" name="search_by" id="search_by_job_description" value="job_description"<?php echo ($_POST['search_by']=='job_description') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" /> <label for="search_by_job_description" class="plain">Job Description</label> 
            </div>
            <div class="p">
            	<div class="label">Date Range</div>
               <input type="radio" name="date_range" id="date_range_all" value="all"<?php echo (empty($_POST['date_range']) || $_POST['date_range']=='all') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" onclick="$('#date_ranges').hide();" /> <label for="date_range_all" class="plain">All Dates</label> 
               <input type="radio" name="date_range" id="date_range_specific" value="specific"<?php echo ($_POST['date_range']=='specific') ? ' checked="checked"' : ''; ?> style="width:auto;border:none;" onclick="$('#date_ranges').show();" /> <label for="date_range_specific" class="plain">In Range</label>
               <div id="date_ranges" style="display:<?php echo ($_POST['date_range']=='specific') ? 'block' : 'none'; ?>;">
               	
                  <div class="label">From:</div>
                  <select name="from_month" id="from_month">
                     <option value="">[Month]</option>
<?php
               for($i=1; $i<=12; $i++){
?>
                     <option value="<?php echo $i; ?>"<?php echo ($_POST['from_month']==$i) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php	
               }
?>
                  </select> 
                  <select name="from_day" id="from_day">
                     <option value="">[Day]</option>
<?php
               for($i=1; $i<=31; $i++){
?>
                     <option value="<?php echo $i; ?>"<?php echo ($_POST['from_day']==$i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
<?php	
               }
?>
                  </select> 
                  <input type="text" name="from_year" id="from_year" value="<?php echo (!empty($_POST['from_year'])) ? htmlentities($_POST['from_year'], ENT_QUOTES, 'utf-8') : date('Y'); ?>" style="width:50px;" maxlength="4" />
                  
                  
                  <br style="clear:left;" />
                  
                  
                  <div class="label">To:</div>
                  <select name="to_month" id="to_month">
                     <option value="">[Month]</option>
<?php
               for($i=1; $i<=12; $i++){
?>
                     <option value="<?php echo $i; ?>"<?php echo ($_POST['to_month']==$i) ? ' selected="selected"' : ''; ?>><?php echo $month_list[$i]; ?></option>
<?php	
               }
?>
                  </select> 
                  <select name="to_day" id="to_day">
                     <option value="">[Day]</option>
<?php
               for($i=1; $i<=31; $i++){
?>
                     <option value="<?php echo $i; ?>"<?php echo ($_POST['to_day']==$i) ? ' selected="selected"' : ''; ?>><?php echo $i; ?></option>
<?php	
               }
?>
                  </select> 
                  <input type="text" name="to_year" id="to_year" value="<?php echo (!empty($_POST['to_year'])) ? htmlentities($_POST['to_year'], ENT_QUOTES, 'utf-8') : date('Y'); ?>" style="width:50px;" maxlength="4" />
               </div> 
            </div>
            <div class="p submit"><input type="submit" name="submit" id="submit" value="Search" /></div>
         </form>
         
         
         
<?php
	if(isset($_POST['submit'])){
?>
			<h2 style="float:left; margin-top:30px;">Search Results</h2>
         <table>
         	<tr>
               <th>Project Name</th>
               <th>Report Date</th>
               <th>General Contractor</th>
               <th>Company</th>
               <th width="50">&nbsp;</th>
            </tr>
<?php
		if($results > 0){
			
			$total_paid = 0;
			foreach($results as $result){
				$class = ($class=='odd') ? 'even' : 'odd';
?>
						<tr class="<?php echo $class; ?>">
               <td><a href="stewards_reports_view.php?rid=<?php echo $result['report_id']; ?>"><?php echo $result['project_name']; ?></a></td>
               <td><?php echo date('n/j/Y', strtotime($result['date_submitted'])); ?></td>
               <td><?php echo $result['general_contractor']; ?></td>
               <td><?php echo $result['company']; ?></td>
               <td>
               	<a href="stewards_reports_view<?php echo ($result['version'] != 2) ? '_v1' : ''; ?>.php?rid=<?php echo $result['report_id']; ?>" title="View" class="icon_view">View</a> 
               	<a href="/download.php?id=<?php echo $result['report_id']; ?>&amp;t=stewards_reports" title="Download PDF" class="icon_pdf">Download PDF</a> 
               </td>
            </tr>
<?php	
			}
		} else {
?>
				<tr>
            	<td colspan="4">Your search returned 0 results.</td>
            </tr>
<?php	
		}
?>
         </table>
<?php
	}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>