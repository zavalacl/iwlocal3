<p class="login">Logged-in as <?php echo $_SESSION['user_info']['username']; ?></p>
<ul id="subnav">
   <li<?php echo ($page=='announcements') ? ' class="current"' : ''; ?>><a href="/members/announcements.php">Announcements</a></li>
   <li<?php echo ($page=='death_notices') ? ' class="current"' : ''; ?>><a href="/members/death_notices.php">Death Notices</a></li>
   <li<?php echo ($page=='news_views') ? ' class="current"' : ''; ?>><a href="/members/news_views.php">News and Views</a></li>
   <li<?php echo ($page=='training') ? ' class="current"' : ''; ?>><a href="/members/training.php">Training</a></li>
   <li<?php echo ($page=='political_action') ? ' class="current"' : ''; ?>><a href="/members/political_action.php">Political Action</a></li>
   <li<?php echo ($page=='document_library') ? ' class="current"' : ''; ?>><a href="/members/document_library.php">Document Library</a></li>
   <li<?php echo ($page=='benefits') ? ' class="current"' : ''; ?>><a href="/members/benefits.php">Benefits</a></li>
   <li<?php echo ($page=='stewards_report') ? ' class="current"' : ''; ?>><a href="/members/stewards_report.php">Steward's Report</a></li>
   <li class="sub<?php echo ($page=='stewards_reports_search') ? ' current' : ''; ?>"><a href="/members/stewards_reports_search.php">Search Reports</a></li>
   <?php /*?><li<?php echo ($page=='account') ? ' class="current"' : ''; ?>><a href="/members/account.php">Pay Dues</a></li><?php */?>
   <li<?php echo ($page=='events') ? ' class="current"' : ''; ?>><a href="/members/events.php">Events Calendar</a></li>
   <li><a href="/members/login.php?logout">Logout</a></li>
</ul>


<h2 style="margin-top:0;">Phone Numbers</h2>
<ul id="phone_numbers"> 
	<li><strong>Union Hall</strong>: (412) 227-6767 <br />
  or 1 (800) 927-3198</li>
	<li><strong>Clearfield Hall</strong>: (814) 765-7535</li>
	<li><strong>Erie Hall</strong>: (814) 898-2060</li>
	<li><strong>Benefit Plans</strong>: (412) 227-6740 <br />
  or 1 (800) 927-3199</li>
	<li><strong>Credit Union</strong>: (412) 471-1133 <br />
  or 1 (800) 471-4928</li>
	<li class="last"><strong>Apprenticeship</strong>: (412) 471-4535 <br />
    or 1 (877) 771-4535</li>
</ul>