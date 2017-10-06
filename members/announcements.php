<?php
	require('config.php');
	$access_level = ACCESS_LEVEL_MEMBER; require("authenticate.php");
	require('functions/announcements.php');
	require('functions/scholarship_applications.php');
	
	
	// Register for Email Updates
	if(isset($_POST['submit'])){
		try {
			
			$required = array('first_name', 'last_name', 'email');
			
			$validator = new Validator($required);
			$validator->noFilter('first_name');
			$validator->noFilter('last_name');
			$validator->isEmail('email');
						
			$filtered = $validator->validateInput();
			$errors = $validator->getErrors();
			
			if(!$errors){
				require('functions/email_registrations.php');
				
				$clean = array_map('escapeData', $filtered);
				
				if(getEmailRegistrationByEmail($clean['email']) <= 0){
				
					if(newEmailRegistration($clean['email'], $clean['first_name'], $clean['last_name']) > 0){
						$alerts->addAlert('You have successfully registered for email updates.');
						
						
						// Send Email Notification
						require('classes/PHPMailer.php');
			
						try {
							$mail = new PHPMailer();
			
							$mail->SetFrom('info@iwlocal3.com');
							//$mail->AddAddress('taylor@taylorcollinsdesign.com');
							$mail->AddAddress('info@iwlocal3.com');
							$mail->Subject = "New Email Registration";
							
							$html = "<b>New Email Registration</b><br>
<br>
Name: {$filtered['first_name']} {$filtered['last_name']} <br>
Email: {$filtered['email']}";
							
							$mail->MsgHTML($html);
							
							$mail->Send();
							
						} catch (phpmailerException $e) {
						  //$alerts->addAlert($e->errorMessage());
						} catch (Exception $e) {
						  //$alerts->addAlert($e->getMessage());
						}
						
						
						unset($_POST);
					} else {
						$alerts->addAlert('You could not be registered for email updates.');
					}
				} else {
					$alerts->addAlert('You have already registered for email updates.');
					unset($_POST);
				}
			} else {
				$alerts->addAlerts($errors);
			}
		} catch(Exception $e){
			$alerts->addAlert('An unknown error occured.');
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Announcements | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/members.css" />
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='announcements'; include("subnav_member.php"); ?>
      </div><!-- div.left -->
      <div class="right">
               
      	<h1>Announcements</h1>
         
         <p>Learn all about the latest Iron Workers Local Union No. 3 news and announcements. Specifically read about: available scholarships and applications; links to industry information; job photographs; convenient links to industry websites; and email registration. If you are unable to view any of the documents on this page, <a href="http://get.adobe.com/reader/" target="_blank">click here</a> to download Adobe Reader for free.</p>
         
         <div id="announcements">
         	<div class="upper">
         
         	<div id="info_links">
            	<h2>Information Links</h2>
               <p>Click the following links to download documents or view articles on industry information.</p>
               <ul>
<?php
			$links = getInformationLinks();
			if($links > 0){
				foreach($links as $link){
					$url = ($link['url']) ? forceURI($link['url']) : '../download.php?t=info_links&amp;id='.$link['link_id'];
?>
					<li><a href="<?php echo $url; ?>" target="_blank"><?php echo $link['title']; ?></a></li>
<?php	
				}
			} else {
?>
					<li>There are currently no information links available.</li>
<?php
			}
?>
               </ul>
            </div><!-- div#info_links -->
            
            <div id="job_pictures">
            	<h2>Job Pictures</h2>
               <p>Click the following links to download documents containing job photographs.</p>
            	 <ul>
<?php
			$documents = getJobPictureLinks();
			if($documents > 0){
				foreach($documents as $document){
?>
					<li><a href="../download.php?t=job_pictures&amp;id=<?php echo $document['document_id']; ?>" target="_blank"><?php echo $document['title']; ?></a></li>
<?php	
				}
			} else {
?>
					<li>There are currently no documents available.</li>
<?php
			}
?>
               </ul>
            </div><!-- div#job_pictures -->
            
            </div><!-- div.upper -->
            <div class="lower">
            
            <div id="other_links">
            	<h2>Other Links</h2>
               <p>The following links are other documents or articles relating to the industry.</p>
               <ul>
<?php
			$links = getLinks();
			if($links > 0){
				foreach($links as $link){
					$url = ($link['url']) ? forceURI($link['url']) : '../download.php?t=links&amp;id='.$link['link_id'];
?>
					<li><a href="<?php echo $url; ?>" target="_blank"><?php echo $link['title']; ?></a></li>
<?php	
				}
			} else {
?>
					<li>There are currently no links available.</li>
<?php
			}
?>
               </ul>
            </div><!-- div#other_links -->
            
            <div id="email_registration">
            	<h2>Email Registration</h2>
               <p>Complete the form below to sign up for email updates.</p>
               
               <?php if($alerts->hasAlerts()) echo $alerts; ?>
               
            	<form action="<?php echo getSelf(); ?>" method="post">
               	<p><label for="first_name" style="text-align:left;"><span class="required">*</span> First Name</label> <input type="text" name="first_name" id="first_name" value="<?php echo htmlentities($_POST['first_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
                  <p><label for="last_name" style="text-align:left;"><span class="required">*</span> Last Name</label> <input type="text" name="last_name" id="last_name" value="<?php echo htmlentities($_POST['last_name'], ENT_QUOTES, 'utf-8'); ?>" /></p>
                  <p><label for="email" style="text-align:left;"><span class="required">*</span> Email</label> <input type="text" name="email" id="email" value="<?php echo htmlentities($_POST['email'], ENT_QUOTES, 'utf-8'); ?>" /></p>
                  <p class="submit" style="padding-left:0;"><input type="submit" name="submit" id="submit" value="Submit" /></p>
               </form>
            </div><!-- div#email_registration -->
            
            <div id="applications">
            	<h2>Scholarship Opportunities</h2>
            	<p>Below are the interactive application links for you to download and apply for scholarships.</p>
               
               <ul>
<?php
			$applications = getScholarshipApplications(1);
			if($applications > 0){
				foreach($applications as $application){
?>
					<li>
               	<a href="../download.php?t=scholarship_applications&amp;id=<?php echo $application['application_id']; ?>" target="_blank"><?php echo $application['title']; ?></a>
<?php 		
					if($application['description']){
?>
						<br /><span class="description"><?php echo $application['description']; ?></span>
<?php
					}
?>
               </li>
<?php	
				}
			} else {
?>
					<li>There are currently no applications available.</li>
<?php
			}
?>
               </ul>
            </div><!-- div#applications -->
         
         	</div><!-- div.lower -->
         </div><!-- div#announcements -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>