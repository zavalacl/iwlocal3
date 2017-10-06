<?php
	require('config.php');
	require('functions/content_areas.php');
	
	// Get Content Areas
	$content_alerts = getContentArea('apprentices_alerts');
	$content_application = getContentArea('apprentices_application');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Apprentices | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />

<style type="text/css">
.apprentice-test-date { background-color: #CCC; border: 1px solid #000; padding: 10px 15px 5px 15px; margin-bottom: 20px; }
.callout-box { background-color: #666; border: 1px solid #000; padding: 10px 15px 5px 15px; margin-top: 5px; margin-bottom: 10px; color: #FFF; }
</style>

<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='apprentices'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='apprenticeships'; include("subnav_apprentices.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Apprentices</h1>
         
         <div class="main_left">
         
<?php
			if($content_alerts['content']){
?>
		     <div class="apprentice-test-date">
					 <?php echo $content_alerts['content']; ?>
					</div>
<?php
			}
?>
         
 				<p>The apprenticeship program offered by Iron Workers Local Union No. 3 helps stimulate and assist with the skill development of new members starting their journey in the iron worker industry.</p> 

				<p>Please review the details and requirements for becoming an apprentice in this highly specialized industry.</p>
         
         	<h2 style="margin-top:0;">What Does Being an Apprentice Mean?</h2>
            
            <p>Are you willing to work hard and get paid good money while you train? That's right! Unlike trade schools, you don't pay us. You'll be on the job earning a good living while you learn the trade. Current apprentices can start at $16 per hour and can earn up to $26, plus accrue health care and other benefits immediately. The apprenticeship program is a three-year commitment and you can earn college credit.</p>

            <p>There's no getting around it – the work is demanding – and much of it is outdoors. And, there are requirements similar to those of other building trade apprenticeship programs:</p>
            <ul>
            	<li>Minimum age of 18</li>
            	<li>
                	High school diploma or GED
<?php
			if($content_application['content']){
?>
                	<div class="callout-box"><?php echo $content_application['content']; ?></div>
<?php
			}
?>
              </li>
            </ul>
            <ul>
                <li>Good physical condition</li>
            	<li>The ability to pass drug and alcohol screenings</li>
            	<li>Have a valid drivers' license and access to a car - many job sites are in remote areas not served by public transportation</li>
            	<li>Pass muster with our apprenticeship committee</li>
            </ul>
            
            <h2>How Does Our Apprenticeship Program Work?</h2>
            
            <p>Our program is a three year program. All of our training (for all three zones) is conducted at our training facility located in the heart of the Strip District of Pittsburgh. Training is conducted year round as you work for one of our contractors. At seven different times throughout the year, you will be required to attend our training facility for one week at a time. That's 280 hours of quality training each year. Each training week is set up to follow the same time schedule as a typical work week, combining classroom instruction with indoor and outdoor hands on training that will simulate the same conditions that you will encounter on the job site. Our program is run under the guidelines of our State Approved Apprenticeship Standards. <a href="/files/Standards_of_Apprenticeship.pdf" target="_blank">Click here to view our Apprenticeship Standards</a>.</p>
            
            <h2>What Is Expected of An Apprentice Once They Are Accepted Into The Program?</h2>    
            
            <p>Being an apprentice is not necessarily an easy task. We run a tight ship and a good work ethic as well as being responsible and dependable is required at all times. All apprentices are expected to act in a professional and mature manner while in school and also in the workplace. They will be subject to follow the rules and regulations of the program as stated in our Apprentice Standards and our Policies and Procedures. <a href="/files/Policies_and_procedures.pdf" target="_blank">Click here to view our Policies and Procedures</a>. Those apprentices that do not  meet our strict standards will be removed from the program.</p>
            
            <h2>How Is My Rate of Pay Determined?</h2>                                                                                          
            
            <p>As an apprentice, you will start out at 55% of what a journeyman in your area is currently making, PLUS you will receive full benefits after around three months of steady employment. From there, your pay will increase as you acquire on the job experience. You will get your first raise after just 700 hours of on the job training and a second raise after you acquire another 700 hours of on the job training. WOW! That's two raises in about 9 months of steady employment. From there, your pay continues to increase as you acquire even more on the job experience.</p>
            
            <h2>What Are My Chances of Steady Employment If I am  Accepted?</h2>
            
            <p> It's true that an iron worker doesn't do a 9 to 5 job and that there are seasonal variations, but there are several things to consider. First, with all of the anticipated construction being planned for western Pennsylvania in the coming years – bridge repairs, a coke battery, hotels, high rises, etc. - there will be work for the foreseeable future. In addition, members of Iron Workers Local Union No. 3 are not restricted to western Pennsylvania. As an iron worker, you can go where you're needed. </p>
            
           <h2>If Work Does Slow Up, Does That Mean That I Will Be The First To Be Laid Off Since I Will Be One of The Newest Members?</h2>
            
            <p>NO! Our union does not go by seniority. Our contractors keep the most dependable and the hardest working ironworkers. It's all up to you! If you work hard, are dependable, and can prove your worth to our contractors, you should be able to keep steady employment. It all comes down to SURVIVAL OF THE FITTEST. Just remember, when you become an apprentice, all of the other apprentices in the program are your competition. It's up to you to show that you are better than the rest and deserve to remain employed.</p>
            
            <h2>What Does the Training Cost?</h2>
            
            <p>The training program itself is free. You will be required to purchase the necessary books each year which is about $75 a year, and you will be required to join our union. There is currently a $35.00 non-refundable application fee required when you submit your application. We do not accept cash, so please submit a check or money order with your application.</p>
            
            <h2>What Happens Once I Sign Up to Take the Test?</h2>
            
            <p>The first step is you will be required to take our aptitude test which will test your math, reading and measurement skills. If you score 80% or higher on our aptitude test, you will then be asked to come in for an interview with our Apprenticeship Committee. Your interview will be scored and if you score is high enough, you will be placed on our acceptance list. When the need for more apprentices arises, we take the top scores from the acceptance list and offer them a spot in the apprenticeship program. Each time a test and interviews are given, the interview scores are added to the acceptance list in the appropriate order. The highest scores are placed near the top and the lower scores are placed near the bottom. Our list is constantly being updated each time we offer testing. Once you make the acceptance list, your score will remain on the list for 2 years. As apprentices are accepted or as new tests are given, you may move up or down on the list depending on your score.</p>
            
            <h2>How Often are you Currently Testing?</h2>
            
            <p>We are currently testing 2-4 time a year.</p>
            
            <h2>I am Currently a High School Senior, do I Have to Have My Diploma Before I Can Apply?</h2>
            
            <p>NO! You only need to have your diploma before you start the program. There is usually a test in early spring. You can apply and take the test in early spring without your diploma, then if you are accepted, you will need your diploma before the start of orientation in June. Then you are able to start you career right after you graduate.</p>
            
            <h2>How often are you accepting Apprentices?</h2>
            
            <p>We are taking apprentices as the need arises. Over the past two years, we have been averaging a new class every 6 months.</p>
            
         </div><!-- div.main_left -->
         <div class="main_right">
         	<img src="/img/inner/apprentices.jpg" alt="" class="shadow" />
            
            <div class="box">
            	<span class="title">Application Form</span><br />
               Click here to get more information on how to apply.<br />
               <a href="application.php" class="learn_more">Apply Here</a>
            </div><!-- div.box -->
            <div class="box">
            	<span class="title">Tell a Friend</span><br />
                Click here to send this information and website to a friend. <br />
               <a href="tell_friend.php" class="learn_more">Send now</a>
            </div><!-- div.box -->
            
         </div><!-- div.main_right -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>