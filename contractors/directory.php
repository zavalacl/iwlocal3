<?php
	require('config.php');
	require('functions/contractors.php');
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Contractor Directory | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="../css/inner.css" />

<style type="text/css">
#directory_nav { width: 100%; padding: 15px 0; margin: 0; background: #062644; }
#directory_nav ul { list-style: none; margin: 0; padding: 0; }
#directory_nav ul li { display: block; margin: 0; padding: 0; float: left; font-size: .85em; }
#directory_nav ul li a { display: inline-block; color: #fff; text-decoration: none; padding: 0 9.5px; }
#directory_nav ul li a:hover { text-decoration: underline; }
#directory_nav ul li { display: block; margin: 0; padding: 0; float: left; font-size: .85em; color: #eee; }
#directory_nav ul li .placeholder { display: inline-block; padding: 0 10.9px; color: #777; }

.directory { width: 774px; padding: 15px 0; border-bottom: 1px solid #ddd; }
.directory .third { width: 240px; padding-right: 25px; margin-bottom: 25px; }
.directory .category .third:nth-child(3n) { clear: left; }
.directory .third.last { padding-right: 0px; }
.directory .title { font-size: .9em; color: #062644; }
.directory .category { width: 100%; display: block; }
.directory .category h1 { margin: 0; color: #6d0111; }
.directory .category .subtitle { font-weight: normal; font-style: italic; width: 100%; margin: 0 0 10px 0; }
.directory .category_title { width: 100%; display: block; padding: 10px 0; font-size: 1.25em; color: #bbb; font-weight: 600; text-align: right; }

.directory_categories { margin: 20px 0; }
.directory_categories ul#list { list-style:none; margin:0; padding:0; height:auto; }
.directory_categories ul#list li { display:block; width:196px; margin:0; padding:0; border-bottom:1px solid #fff; }
.directory_categories ul#list li a { display:block; padding:2px 5px 2px 0px; margin-bottom:2px; text-decoration:none; font-size:.95em; }
.directory_categories .show_hide { text-decoration: none; }
</style>
<?php include("analytics.php"); ?>
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
  <div id="main" class="inner">
    <div class="left">
    	<?php $page='directory'; include("subnav_contractors.php"); ?>
      
      <div class="directory_categories">
      	<a href="#" class="show_hide"><p><strong>Contractor Categories</strong></p></a>
      	<ul id="list">
<?php
			
			// Contractor categories
			$categories = getContractorCategories();
			if($categories > 0){
				foreach($categories as $category){
?>
       	  <li><a href="#<?php echo safeLink($category['category']); ?>"><?php echo $category['category']; ?></a></li>
<?php
				}
			}
?>
      	</ul>
      </div>
      <!-- div.directory_categories -->
    </div>
    <!-- div.left -->
    <div class="right">
      <h1 id="top">Contractor Directory</h1>
      
      <p>The goal of this directory is to create an informational source for contractors, owners, plant managers, etc. to utilize on a day-to-day basis. By hiring these companies, a readily available and highly-trained workforce will be on the jobsite to complete the various parts of the project both on-time and on-budget. Employing safe, well-trained, local workers not only adds value to the employer&rsquo;s bottom line, it also benefits the surrounding community. Please find this directory to be a useful guide in helping to make the right industry &quot;connections&quot;.</p>
      <p>&nbsp;</p>
      <div id="directory_nav">
        <ul>
<?php
			
		// First letter of categories
		$first_letters = getContractorCategoryFirstLetters();
				
		
		// Loop through alphabet
		$letters = range('A', 'Z');
		foreach($letters as $letter){
			if( in_array($letter, $first_letters) ){
?>
          <li><a href="#<?php echo $letter; ?>"><?php echo $letter; ?></a></li>
<?php
			} else {
?>
					<li><span class="placeholder"><?php echo $letter; ?></span></li>
<?php
			}
		}
?>
          <li><a href="#top">Top</a></li>
        </ul>
      </div>
      <!-- #directory_nav -->
      
      
<?php
	
	// First letter of categories
	$letters = getContractorCategoryFirstLetters();
	if($letters > 0){
		foreach($letters as $letter){
			
			
			// Categories
			$categories = getContractorCategoriesByFirstLetter($letter);
?>
      
      <div class="directory" id="<?php echo $letter; ?>">
        <div class="category_title"><?php echo $letter; ?></div>
        
<?php
			foreach($categories as $category){
				
				
				// Contractors
				$contractors = getContractorsInCategory($category['category_id']);
				if($contractors){
?>
        <div class="category">
          <h1 id="<?php echo safeLink($category['category']); ?>"><?php echo $category['category']; ?></h1>
          <div class="subtitle"></div>
<?php
					$i=1;
					foreach($contractors as $contractor){
?>          
	          <div class="third<?php echo ($i%3 == 0) ? ' last' : ''; ?>">
	          	<strong><?php echo htmlentities($contractor['name']); ?></strong> <br />
	            <?php echo htmlentities($contractor['address']); ?><br />
	            <?php echo ($contractor['address_2']) ? htmlentities($contractor['address_2']) . '<br />' : ''; ?>
	            <?php echo htmlentities($contractor['city']); ?>, <?php echo htmlentities($contractor['state']); ?> <?php echo htmlentities($contractor['zip']); ?><br />
	            Office: <?php echo formatPhone($contractor['phone']); ?><br />
	            <?php echo ($contractor['fax']) ? formatPhone($contractor['fax']) . '<br />'  : ''; ?>
	            <?php echo ($contractor['url']) ? '<a href="' . forceURI($contractor['url']) . '" target="_blank">' . htmlentities($contractor['url']) . '</a><br />'  : ''; ?>
							
						<?php if($contractor['specialties']): ?>
	            <em>Specialty/Certification:</em>
	            <?php echo nl2br(htmlentities($contractor['specialties'])); ?>
	           <?php endif; ?>
	            
	          </div>
	          <!-- div.third --> 
<?php
						$i++;
					}
?>
        </div>
        <!-- div.category -->
<?php
	 			}
	 		}
?>     
      </div>
      <!-- div.directory #a-->
<?php
		}
	}
?> 
     
      
    </div>
    <!-- div.right --> 
  </div>
  <!-- div#main -->
   <?php include("footer.php"); ?>
</div>
<!--Sticky Directory Nav-->
<script type="text/javascript" src="/js/scrollfix.js"></script> 
<script type="text/javascript">
	$('#directory_nav').scrollFix();
</script>
<!--Contractor Categories Nav-->
<!--<script type="text/javascript">
	$("#list").hide();
    $(".show_hide").show();
 
    $('.show_hide').click(function(){
    $("#list").slideToggle();
 });
</script>-->
</body>
</html>