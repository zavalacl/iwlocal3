<div id="header">
   <a href="/" class="logo">Iron Workers Local Union No. 3</a>
   <ul id="courtesy_nav">
   	<li><a href="/faqs/">FAQs</a></li>
      <li><a href="/contact_us.php">Contact Us</a></li>
      <li><a href="/members/login.php">Member Login</a></li>
   </ul>
   <ul id="nav">
      <li<?php echo (isset($nav_page) && $nav_page=='about_us') ? ' class="current"' : ''; ?>><a href="/about_us/" class="about_us">About Us</a></li>
      <li<?php echo (isset($nav_page) && $nav_page=='whats_new') ? ' class="current"' : ''; ?>><a href="/whats_new/" class="whats_new">What's New</a></li>
      <li<?php echo (isset($nav_page) && $nav_page=='contractors') ? ' class="current"' : ''; ?>><a href="/contractors/" class="contractors">Contractors</a></li>
      <li<?php echo (isset($nav_page) && $nav_page=='our_work') ? ' class="current"' : ''; ?>><a href="/our_work/" class="our_work">Our Work</a></li>
      <li<?php echo (isset($nav_page) && $nav_page=='our_services') ? ' class="current"' : ''; ?>><a href="/our_services/" class="our_services">Our Services</a></li>
      <li<?php echo (isset($nav_page) && $nav_page=='apprentices') ? ' class="current"' : ''; ?>><a href="http://apprentice.iwlocal3.com" class="apprentices">Apprentices</a></li>
   </ul>
</div><!-- div#header -->