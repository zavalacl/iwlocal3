<p class="login">Logged-in as <?php echo $_SESSION['user_info']['username']; ?></p>
<ul id="subnav">
	<li class="h1" style="margin-top:0;">Member Accounts</li>
   <li class="h2" style="margin-top:0;">Announcements</li>
   <li class="sub<?php echo ($page=='information_links') ? ' current' : ''; ?>"><a href="/admin/information_links.php">Information Links</a></li>
   <li class="sub<?php echo ($page=='job_pictures') ? ' current' : ''; ?>"><a href="/admin/job_pictures.php">Job Pictures</a></li>
   <li class="sub<?php echo ($page=='other_links') ? ' current' : ''; ?>"><a href="/admin/other_links.php">Other Links</a></li>
   <li class="sub<?php echo ($page=='email_registration') ? ' current' : ''; ?>"><a href="/admin/email_registration.php">Email Registration</a></li>
   <li class="sub<?php echo ($page=='scholarship_applications') ? ' current' : ''; ?>"><a href="/admin/scholarship_applications.php">Scholarship Applications</a></li>
   
   <li<?php echo ($page=='death_notices') ? ' class="current"' : ''; ?>><a href="/admin/death_notices.php">Death Notices</a></li>
   <li<?php echo ($page=='news_views') ? ' class="current"' : ''; ?>><a href="/admin/news_views.php">News &amp; Views</a></li>
   <li<?php echo ($page=='training') ? ' class="current"' : ''; ?>><a href="/admin/training.php">Training</a></li>
   <li<?php echo ($page=='document_library') ? ' class="current"' : ''; ?>><a href="/admin/document_library.php">Document Library</a></li>
   <li<?php echo ($page=='political_action_files') ? ' class="current"' : ''; ?>><a href="/admin/political_action_files.php">Political Action</a></li>
   <li<?php echo ($page=='benefits') ? ' class="current"' : ''; ?>><a href="/admin/benefits.php">Benefits</a></li>
   <li<?php echo ($page=='events') ? ' class="current"' : ''; ?>><a href="/admin/events.php">Events Calendar</a></li>
   
   <li class="h1">Contractor Accounts</li>
   <li class="sub<?php echo ($page=='contractor_documents') ? ' current' : ''; ?>"><a href="/admin/contractor_documents.php">Contractor Documents</a></li>
   <?php /*<li class="sub<?php echo ($page=='featured_contractor') ? ' current' : ''; ?>"><a href="/admin/featured_contractor.php">Featured Contractor</a></li>*/ ?>
   
   <li class="h1">Contractor Directory</li>
   <li class="sub<?php echo ($page=='contractors_categories') ? ' current' : ''; ?>"><a href="/admin/contractors_categories.php">Contractor Categories</a></li>
   <li class="sub<?php echo ($page=='contractors') ? ' current' : ''; ?>"><a href="/admin/contractors.php">Contractors</a></li>
   
   <li class="h1">Miscellaneous</li>
   <li<?php echo ($page=='news') ? ' class="current"' : ''; ?>><a href="/admin/news.php">Latest News</a></li>
   <li<?php echo ($page=='projects') ? ' class="current"' : ''; ?>><a href="/admin/projects.php">Project Gallery</a></li>
   <li<?php echo ($page=='faqs') ? ' class="current"' : ''; ?>><a href="/admin/faqs.php">FAQs</a></li>
   
   <li<?php echo ($page=='apprentices') ? ' class="current"' : ''; ?>><a href="/admin/apprentices.php">Apprentices</a></li>
   <li<?php echo ($page=='officers') ? ' class="current"' : ''; ?>><a href="/admin/officers.php">Officers</a></li>
   <li<?php echo ($page=='contractors_reps') ? ' class="current"' : ''; ?>><a href="/admin/contractors_reps.php">Contractor Reps</a></li>
   
   <li<?php echo ($page=='stewards_reports') ? ' class="current"' : ''; ?>><a href="/admin/stewards_reports.php">Steward's Reports</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_search') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_search.php">Search Reports</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_projects') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_projects.php">Projects</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_locations') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_locations.php">Project Locations</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_counties') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_counties.php">Counties</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_contractors') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_contractors.php">General Contractors</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_work_descriptions') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_work_description_categories.php">Work Descriptions</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_employers') ? ' current' : ''; ?>"><a href="/admin/stewards_reports_employers.php">Employers</a></li>
  
   <li<?php echo ($page=='users') ? ' class="current"' : ''; ?>><a href="/admin/users.php">User Accounts</a></li>
   <li class="sub<?php echo ($page=='users_upload_csv') ? ' current' : ''; ?>"><a href="/admin/users_upload_csv.php">Upload .csv File</a></li>
   <li class="sub<?php echo ($page=='users_upload_txt') ? ' current' : ''; ?>"><a href="/admin/users_upload_txt.php">Upload .txt File</a></li>
   
   <li<?php echo ($page=='document_repository') ? ' class="current"' : ''; ?>><a href="/admin/document_repository.php">Document Repository</a></li>
   <li><a href="/members/login.php?logout">Logout</a></li>
</ul>
