<?php
	require("$_SERVER[DOCUMENT_ROOT]/inc/config.php");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/home.css" />
<link type="text/css" rel="stylesheet" href="/js/fancybox/fancybox.css" media="screen" />
<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/fancybox/easing.js"></script>
<script type="text/javascript" src="/js/fancybox/fancybox.js"></script>
<script type="text/javascript" src="/js/jquery.cycle.all.min.js"></script>
<script type="text/javascript">
// Flash Banner
var flashvars = {};
var params = {wmode:'transparent'};
var attributes = {};

swfobject.embedSWF("swf/banner.swf", "flashContent", "1100", "260", "9.0.0",false, flashvars, params, attributes);


$(document).ready(function(){

	// Slideshow
	$('#banner ul').cycle({
		fx: 'fade'
	});

	// Lightbox
	$("a.fancybox").fancybox({
		'width'	: 405,
		'height': 342,
		'type'	: 'iframe'
	});
	$.fancybox( $("#apprentice-popup").html(),{
    'width'     			: 'auto',
    'height'    			: 'auto',
		'overlayOpacity'	: 0.7,
		'overlayColor'		: '#fff',


    });
});
</script>

<?php include("analytics.php"); ?>
</head>

<body>
<div id="apprentice-popup"><a href="http://apprentice.iwlocal3.com"><img src="/img/IW3-Apprentice-Web-2016_FINAL.jpg" alt="" /></a></div>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="banner">
   	<div id="flashContent"><img src="/img/_flash_banner.jpg" alt="" /></div>
      <ul>
      	<li><img src="img/banner/convention_center.jpg" alt="" /></li>
         <li><img src="img/banner/childrens_hospital.jpg" alt="" /></li>
         <li><img src="img/banner/heinz_field.jpg" alt="" /></li>
         <li><img src="img/banner/hard_hat.jpg" alt="" /></li>
      </ul>
   </div>
   <div id="main" class="home">
   	<div class="left">
      	<h1>What We Do</h1>

         <ul id="what_we_do">
         	<li>
            	<a href="/our_services/structural_steel_erection.php"><img src="/img/home/wwd_steel_erection.jpg" alt="Structural Steel Erection" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/structural_steel_erection.php" class="title">Structural Steel Erection</a><br />
               	<strong>Job Site:</strong> CONSOL Energy Center – Pittsburgh, PA<br />
                  <strong>Job:</strong> Steel Erection<br />
                  <strong>Contractor:</strong> <a href="http://www.centurysteel.com" target="_blank">Century Steel Erectors</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/precast_concrete_construction.php"><img src="/img/home/wwd_precast_const.jpg" alt="Pre-Cast Concrete Construction" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/precast_concrete_construction.php" class="title">Pre-Cast Concrete Construction</a><br />
               	<strong>Jobsite:</strong> Cork Factory Parking Garage – Pittsburgh, PA<br />
                  <strong>Job:</strong> Pre-Cast Construction<br />
                  <strong>Contractor:</strong> <a href="http://www.sidleyprecast.com" target="_blank">Sidley Precast Group</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/bridge_erection_repair.php"><img src="/img/home/wwd_skyscrape_bridge.jpg" alt="Bridge Erection &amp; Repair" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/bridge_erection_repair.php" class="title">Bridge Erection &amp; Repair</a><br />
               	<strong>Jobsite:</strong> Sixth Street Bridge (Roberto Clemente Bridge) – Pittsburgh, PA<br />
                  <strong>Job:</strong> Erection<br />
                  <strong>Contractor:</strong> <a href="http://www.americanbridge.net" target="_blank">American Bridge Company</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/ornamental.php"><img src="/img/home/wwd_ornamental.jpg" alt="Ornamental Iron / Curtain Wall" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/ornamental.php" class="title">Ornamental Iron / Curtain Wall</a><br />
               	<strong>Job Site:</strong> Phipps Conservatory – Pittsburgh, PA<br />
                  <strong>Job:</strong> Curtain Wall / Glass<br />
                  <strong>Contractor:</strong> <a href="http://www.dmproductsinc.com" target="_blank">D-M Products Incorporated</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/reinforcing.php"><img src="/img/home/wwd_reinforcing.jpg" alt="Concrete / Reinforcing Steel" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/reinforcing.php" class="title">Concrete / Reinforcing Steel</a><br />
               	<strong>Job Site:</strong> Bayfront Convention Center – Erie, PA<br />
                  <strong>Job:</strong> Reinforcing Concrete<br />
                  <strong>Contractor:</strong> <a href="http://www.clevelandcement.com" target="_blank">Cleveland Cement Contractors, Inc.</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/preengineered_metal_buildings.php"><img src="/img/home/wwd_preengineered.jpg" alt="Pre-Engineered Metal Buildings" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/preengineered_metal_buildings.php" class="title">Pre-Engineered Metal Buildings</a><br />
               	<strong>Jobsite:</strong> Leetsdale Industrial Park - Leetsdale, PA<br />
                  <strong>Job:</strong> Sheetiiing / Insulation / Erection<br />
                  <strong>Contractor:</strong> <a href="http://www.iwea.org/association_members/profile.php?mid=27" target="_blank">Lagoni Erection</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/welding.php"><img src="/img/home/welding.jpg" alt="Welding" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/welding.php" class="title">Welding</a><br />
               	<strong>Jobsite:</strong> Mt. Nittany Medical Center - State College, PA<br />
                  <strong>Job: </strong>Welding<br />
                  <strong>Contractor:</strong> <a href="http://www.tuscarorarigging.com" target="_blank">Tuscarora Rigging Inc.</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/rigging.php"><img src="/img/home/wwd_rigging.jpg" alt="Rigging / Machinery Moving" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/rigging.php" class="title">Rigging / Machinery Moving</a><br />
               	<strong>Job Site:</strong> Beaver Valley Power Station - Shippingport, PA<br />
                  <strong>Job:</strong> Rigging &amp; Machinery Moving<br />
                  <strong>Contractor:</strong> <a href="http://www.dayzim.com" target="_blank">Day &amp; Zimmermann NPS</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/post_tensioning.php"><img src="/img/home/post_tensioning.jpg" alt="Post Tensioning" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/post_tensioning.php" class="title">Post Tensioning</a><br />
               	<strong>Job Site:</strong> VA Hospital Parking Garage – Oakland, PA<br />
                  <strong>Job:</strong> Cast in Place / Rebar / Post Tensioning<br />
                  <strong>Contractor:</strong> <a href="http://www.iwea.org/association_members/profile.php?mid=37" target="_blank">Tri City Steel</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/industrial_construction.php"><img src="/img/home/wwd_indus_construction.jpg" alt="Industrial Construction" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/industrial_construction.php" class="title">Industrial Construction</a><br />
               	<strong>Job Site:</strong> Cheswick Power Station - Springdale, PA<br />
                  <strong>Job:</strong> Industrial Construction<br />
                  <strong>Contractor:</strong> <a href="http://www.stevensec.com" target="_blank">STEVENS</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/industrial_maintenance.php"><img src="/img/home/wwd_indus_maintenance.jpg" alt="Industrial Plant Maintenance" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/industrial_maintenance.php" class="title">Industrial Plant Maintenance</a><br />
                  <strong>Job Site:</strong> Pittsburgh Annealing Box Company - Beaver, PA<br />
                  <strong>Job:</strong> Gantry Cranes<br />
                  <strong>Contractor:</strong> <a href="http://www.songersteelservices.com" target="_blank">SSSI</a>
               </div>
            </li>
            <li>
            	<a href="/our_services/wind_turbine_construction.php"><img src="/img/home/wind_turbine.jpg" alt="Wind Turbine Construction" class="shadow" /></a>
               <div class="info">
               	<a href="/our_services/wind_turbine_construction.php" class="title">Wind Turbine Construction</a><br />
               	<strong>Job Site:</strong> Beaverdale Windfarm – Summerhill Township, PA<br />
                  <strong>Job:</strong> Foundation / Erection<br />
                  <strong>Contractor:</strong> <a href="http://www.davisjdsteel.com" target="_blank">Davis JD Steel</a> / <a href="http://www.whiteconstruction.com" target="_blank">White Construction Inc.</a>
               </div>
            </li>
         </ul>
     </div><!-- div.left -->
      <div class="right">
      	<h1>About Iron Workers Local Union No. 3</h1>

         <div id="video">
         	<a href="video/?vf=By Day We build families Re_F8_Lg.flv" class="fancybox"><img src="/img/_flash_video.jpg" alt="" /></a>
         </div>

         <p>The statement, “We don’t go to the office, we build it” rings true with Iron Workers Local Union No. 3.  For over 100 years, these men and women have proudly completed thousands of projects on time and on budget throughout Clearfield, Erie and Western Pennsylvania.  Navigate through our website to learn how countless customers have been satisfied, the membership is continually growing, and our rich history coupled with our quality work helps to build the future. You will find:</p>

			<ul>
         	<li>Exciting information about how you can become an Iron Worker, starting with the available <strong class="highlight">apprentice programs</strong>.</li>
            <li>Hiring an Iron Worker brings the <strong class="highlight">special skill set</strong> and <strong class="highlight">service offerings</strong> that only an Iron Worker can bring to your next project.</li>
            <li>Information on <strong class="highlight">specialized service sectors</strong>: Structural Steel Erection, Pre-Cast Concrete Construction, Bridge Erection &amp; Repair, Ornamental Iron/Curtain Wall, Concrete/Reinforcing Steel, Pre-Engineered Metal Buildings, Welding, Rigging/Machinery Moving, Post Tensioning, Industrial Construction, Industrial Plant Maintenance, and Wind Turbine Construction.</li>
            <li><strong class="highlight">Photo gallery</strong> that showcases the diversity of projects created by Iron Workers throughout many decades.</li>
            <li>Content for <strong class="highlight">members only</strong>, providing the latest information of interest about Iron Workers Local Union No. 3.</li>
         </ul>

         <div class="lower">
         	<div class="a">
            	<span class="title">Project Gallery</span><br />
               Highlights of the exhibition<br />
               of our work include: sports<br />
               arenas, corporate headquarters,<br />
               entertainment venues,<br />
               and hospitals, to name a few.<br />
               <a href="/our_work/" class="learn_more">View now</a>
               <br /><br />
               <a href="/our_work/"><img src="/img/home/project_gallery.jpg" alt="Project Gallery" class="shadow" /></a>
            </div><!-- div.a -->
            <div class="b">
            	<span class="title">Member Login</span><br />
               Members - log into<br />
               your account here.<br />
               <a href="/members/login.php" class="learn_more">Login</a>
               <br /><br /><br /><br /><br />
               <a href="/members/login.php"><img src="/img/home/members.jpg" alt="Member Login" class="shadow" /></a>
            </div><!-- div.b -->
            <div class="c">
            	<span class="title">Contractor Login</span><br />
               Contractors - log into<br />
               your account here.<br />
               <a href="/contractors/login.php" class="learn_more">Login</a>
               <br /><br /><br /><br /><br />
               <a href="/contractors/login.php"><img src="/img/home/contractors.jpg" alt="Contractors Login" class="shadow" /></a>
            </div><!-- div.c -->
         </div>
         <div class="pens_logo"><img src="img/home/pens_logo.jpg" alt="Official Partner of the Pittsburgh Penguins" width="200" height="52" /></div><!-- pens_logo -->
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>
