<?php
	require('config.php');
	$require_admin=true; require("authenticate.php");
	require_once('functions/users.php');
	require_once('classes/Paginator.php');
	
	if(isset($_GET['nu'])) $alerts->addAlert('The user account was successfully added.', 'success');
	
	// Delete a User
	if(isset($_GET['d1'])){
		$did = escapeData($_GET['d1']);
		if(deleteUser($did) > 0)
			$alerts->addAlert('The user account was successfully deleted.', 'success');
		else
			$alerts->addAlert('The user account could not be deleted.');
	}
	
	
	// Search
	if(!empty($_GET['search'])){
		$search = smashSpaces($_GET['search']);
		$words = explode(' ', $search);
		if(count($words) > 1){
			
			$string = "";
			foreach($words as $word){
				$word = escapeData($word);
				$string .= " OR `first_name` LIKE '%$word%' OR `last_name` LIKE '%$word%'";
			}
			$string = ltrim($string, ' OR');
			
			$users = selectArrayQuery("SELECT `user_id`, `first_name`, `last_name`, `username`, `access_level`, `active`, `last_login` 
										  FROM `users` 
										  WHERE $string
										  ORDER BY `last_name` ASC, `first_name` ASC");
		} else {
		
			$users = selectArrayQuery("SELECT `user_id`, `first_name`, `last_name`, `username`, `access_level`, `active`, `last_login` 
										  FROM `users` 
										  WHERE `first_name` LIKE '%$search%' OR `last_name` LIKE '%$search%' OR `username`='$search'
										  ORDER BY `last_name` ASC, `first_name` ASC ");
		}
				
	// Get All Users
	} else {
		
		$total_users = selectQuery("SELECT COUNT(`user_id`) FROM `users`");
		$total_users = $total_users[0];
		
		
		// Pagination	
		$pages = new Paginator();
		$pages->items_total = $total_users;
		$pages->mid_range = 9;
		$pages->items_per_page = 50;
		$pages->paginate();
		
		$users = selectArrayQuery("SELECT `user_id`, `first_name`, `last_name`, `username`, `access_level`, `active`, `last_login` FROM `users` ORDER BY `last_name` ASC, `first_name` ASC {$pages->limit}");
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="International Association of Bridge, Structural, Ornamental, and Reinforcing Iron Workers - AFL-CIO" />
<meta name="keywords" content="Iron Workers,Ironworkers,Pittsburgh Ironworkers,Pittsburgh Iron Workers,Pittsburgh Iron Workers Local Number 3,Local 3,Pittsburgh Apprenticeship programs,Apprenticeships Programs,Apprentice,Trade schools,Pittsburgh Trade schools,Pittsburgh jobs,Unions,Pittsburgh unions,Labor unions,Pittsburgh careers,Benefits,Construction trades,Careers,Steelworkers,Construction careers,Outdoor careers,Outdoor trades,Rewarding careers,Contractors,Pittsburgh construction,Ironworkers International,Engineering,Training,Pre-cast construction,Bridges,Reinforcing,Ornamental,Rebar,Pre-Engineered metal buildings,High-rise buildings,Cowboys in the sky,Erie Construction,Working outdoors,Downtown Pittsburgh,Skyscrapers" />
<title>Admin: User Accounts | Iron Workers Local Union No. 3 | Western and Central PA</title>
<?php include("common.php"); ?>
<link type="text/css" rel="stylesheet" href="/css/inner.css" />
<link type="text/css" rel="stylesheet" href="/css/admin.css" />
</head>

<body>
<div id="wrapper">
	<?php include("header.php"); ?>
   <div id="main" class="inner">
   	<div class="left">
      	<?php $page='users'; include("subnav_admin.php"); ?>
         
         <h2>User Search</h2>
         
         <p>Search by name or book number.</p>
         
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
         
         <form action="<?php echo getSelf(); ?>" method="get">
         	<p><input type="text" name="search" id="search" value="<?php echo htmlentities($_GET['search'], ENT_QUOTES, 'utf-8'); ?>" style="width:190px;" /></p>
            <p class="submit" style="padding-left:0;"><input type="submit" name="submit" id="submit" value="Search" /></p>
         </form>
         
      </div><!-- div.left -->
      <div class="right">
      
      	<a href="users_add.php" class="icon_add">Add User Account</a>
      	
      	<h1>Admin: User Accounts</h1>
                  
         <?php if($alerts->hasAlerts()) echo $alerts; ?>
                  
         <table>
            <tr>
            	<th>Name</th>
               <th>Username/Book Number</th>
               <th>User Type</th>
               <th>Active</th>
               <th>Last Login</th>
               <th width="36">&nbsp;</th>
            </tr>
<?php
		if($users > 0){
			foreach($users as $user){
				$bkgd = (isset($bkgd) && $bkgd=='#ffffff') ? '#efefef' : '#ffffff';
?>
            <tr style="background-color:<?php echo $bkgd; ?>;">
               <td><?php echo $user['first_name'].' '.$user['last_name']; ?></td>
               <td><?php echo $user['username']; ?></td>
               <td><?php echo printUserType($user['access_level']); ?></td>
               <td><?php echo ($user['active']) ? 'Yes' : 'No'; ?></td>
               <td><?php echo ($user['last_login']) ? date('n/j/Y', strtotime($user['last_login'])) : 'N/A'; ?></td>
               <td>
                  <a href="users_edit.php?uid=<?php echo $user['user_id']; ?>" class="icon_edit">Edit</a>
                  <a href="javascript:confirm_deletion(1,<?php echo $user['user_id']; ?>,'<?php echo getSelf(); ?>','user account',0);" class="icon_delete">Delete</a>
               </td>
            </tr>
<?php
			}
		} else {
?>
            <tr>
               <td colspan="6">No users were found.</td>
            </tr>
<?php
		}
?>
         </table>
         
<?php
			// Pagination
			if($pages->num_pages > 1){ 
?>	
        <div class="pagination">
           <?php echo $pages->display_pages(); ?>
        </div>
<?php	
   		}
?>
         
      </div><!-- div.right -->
   </div><!-- div#main -->
   <?php include("footer.php"); ?>
</div>
</body>
</html>