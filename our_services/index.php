<?php
	require('config.php');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Our Services | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link rel="stylesheet" type="text/css" href="/js/fancybox/fancybox.css" media="screen" />
<style type="text/css">
#boxes {
	display:none;
}
div.box {
	padding:10px;
	color:#222;
	font-size:.9em;
}
div.box h2 {
	margin-top:0;
}
</style>
<script type="text/javascript" src="/js/fancybox/fancybox.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
	$("a.popup").fancybox({
		autoDimensions: false,
		width: 600,
		height: 400
	});
});
</script>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php $nav_page='our_services'; include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='our_services'; include("subnav_our_services.php"); ?>
      </div><!-- div.left -->
      <div class="right">
      	<h1>Our Services</h1>
         
         <p>Iron Workers are known for their superior training and attention to quality, safety, service, timing and management which enables them, along with their signatory contractors and partners, to deliver superior results and the best value for every dollar you spend.</p>

         <p>Having received the specialty training needed to meet the complex challenges that arise on each project, the Iron Workers are able to help contractors achieve their goals on the job site.  For more information on our training, please <a href="#box_training" class="popup">click here</a>.</p>
         
         <p>The following is a comprehensive list of the services available to you:</p>
         
         <ul class="services">
            <li>Structural Steel Erection</li>
            <li>Pre-Cast Concrete Construction</li>
            <li>Bridge Erection &amp; Repair</li>
            <li>Ornamental Iron / Curtain Wall</li>
            <li>Concrete / Reinforcing Steel</li>
            <li>Pre-Engineered Metal Buildings</li>
            <li>Welding</li>
            <li>Rigging / Machinery Moving</li>
            <li>Post Tensioning</li>
            <li>Industrial Construction</li>
            <li>Industrial Plant Maintenance</li>
            <li>Wind Turbine Construction</li>
         </ul>
         
         <div id="boxes">
            <div id="box_training" class="box">
               <h2>Training</h2>
               In any field it is critical that a person is trained in what he or she does for a living.  With Iron Workers Local Union No. 3, training is an on-going process.  During the federally-approved apprenticeship program, iron workers are taught inside and outside the state-of-the-art training facility.  While the 840 hours spent in the classroom includes the standardized core curriculum, welding theory and procedure, blueprint reading, and safety standards, iron workers are learning to perfect their craft during the hands on classroom and on-the-job training.  Not only are apprentices learning the skills necessary to apply their craft on projects, but they are also taught the correct behaviors such as accountability, punctuality, and initiative which allow them to excel on your job site.  Once an apprenticeship is completed, the training and education continues with specialized courses, certifications, and advanced safety and health classes.  Approximately $45,000,000 is spent annually on apprenticeship and journeymen upgrade training.  Journeymen are encouraged to participate in the upgrade programs such as foreman training and welding certification so they can continually strive to increase knowledge, greater professionalism, and take the next step up in his/her career.  The international iron workers union believes providing a well-trained, highly-skilled workforce is the most important resource we provide our customers.
            </div>
         </div><!-- div#boxes -->
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>