<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 12, 2020
    Description: Index PHP

----------------->
<?php
	session_start();
	require 'connect.php';

	if(!isset($_SESSION['username']))
	{
		$_SESSION['username'] = 'Guest';

	}

	if(isset($_POST['act']))
	{

		if($_POST['act'] === 'logout')
		{
			unset($_SESSION['id']);
			unset($_SESSION['admin']);
			unset($_SESSION['username']);
			unset($_SESSION['firstname']);
			unset($_SESSION['lastname']);
			unset($_SESSION['streetaddy']);
			unset($_SESSION['city']);
			unset($_SESSION['province']);
			unset($_SESSION['email']);
			unset($_SESSION['phone']);
			session_unset();
		}
	}


	if(isset($_GET['sort']))
	{
		if($_GET['sort'] === 'created_At')
		{
			$sort = 'c.created_At';
		}
		else if($_GET['sort'] === 'username')
		{
			$sort = 'u.username';
		}
		else if($_GET['sort'] === 'userIdFK')
		{
			$sort = 'c.userIdFK';
		}

	}	

		$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);
	
		$query = "SELECT c.id, c.userIdFK, c.comments, c.created_At, u.username FROM comments c LEFT JOIN user u ON u.id = c.userIdFK ORDER BY $sort DESC";

        $statement = $db->prepare($query); 

        $statement->execute(); 

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="cfstyles.css" />
	<title>CF Computing</title>
</head>
<body>
	<header>
		<?php if(!isset($_SESSION['id'])): ?>
			<h1>Welcome to CF Computing : Home</h1>
		<?php else: ?>
			<h1>Welcome to CF Computing : Home</h1>
			<form action="index.php" method="post">
				<h2>User: <?= $_SESSION['username'] ?></h2>
				<h2><input type="submit" name="act" value="logout"></h2>
			</form>
		<?php endif ?>
	</header>
	<nav id = "banner">
		<ul>
			<li><a href = "index.php">Home</a></li>
			<?php if(!isset($_SESSION['id'])): ?>
				<li><a href = "login.php">Login</a></li>
			<?php else: ?>
				<li><a href = "userinfo.php">User Info</a></li>
			<?php endif ?>
			<li><a href = "services.php">Services</a></li>
			<li><a href = "contact.php">Contact Us</a></li>
			<li><a href = "about.php">About Us</a></li>
		</ul>
	</nav>

		<form method="post" action="search.php" id="searchform">
		<input type="text" name="search" placeholder="Search for services">
		<input type="submit" name="submit" value="Search">
	</form>
	
	<div id= "content">
		<p align="center">Welcome to my page, check out my various pages to see what we offer.</p> 
	</div>
	<div class="mylogo">
		<!-- This is MY personal LOGO from my real business I ran so there is no credit for the image except to myself :) -->
	</div>

		<div id="comments">
			<br /><p><h3>Please pick a sorting option to see all the comments</h3></p><br />
			<br /><a href="index.php?sort=created_At">Sort by Created_at</a><br />
			<br /><a href="index.php?sort=username">Sort by username</a><br />
			<br /><a href="index.php?sort=userIdFK">Sort by user id</a><br />
		
			<h4><a href="createComment.php">Create a comment</a></h4>
		</div>

        <?php while ($row = $statement->fetch()): ?>	

        <div id="comments">
          <h4><?= "Created at: " . $row['created_At'] ?></h4>
          <p>
          	<?= "ID: " . $row['userIdFK'] ?>
          	<br />
          	<?= "Username: " . $row['username'] ?>
          	<br />
          	<br />
            <?= $row['comments'] ?>
            
          </p>
          <p>
            <small>
            	
              <?php if(isset($_SESSION['admin'])): ?>
              	<a href="updateComments.php?id=<?= $row['id'] ?>">edit</a>
<!--               <form action="updateDeleteServices.php?id={$row['id']}" method="GET">

                <input type="submit" name="command" value="edit">
              </form> -->
              <?php endif ?>
            </small>
          </p>

        </div>


    <?php endwhile ?>
	<footer>
		<nav id = "footerBanner">
			<ul>
			<li><a href = "index.php">Home</a></li>
			<?php if(!isset($_SESSION['id'])): ?>
				<li><a href = "login.php">Login</a></li>
			<?php else: ?>
				<li><a href = "userinfo.php">User Info</a></li>
			<?php endif ?>
			<li><a href = "services.php">Services</a></li>
			<li><a href = "contact.php">Contact Us</a></li>
			<li><a href = "about.php">About Us</a></li>
			</ul>
			<p>CF Computing</p>
			<img src = "keyboard.jpg" alt = "keyboard" id ="keyboard" />
		</nav>
		<!-- image credit(both images) : https://www.google.com/search?q=computer+images&rlz=1C1GCEA_enCA831CA831&tbm=isch&sxsrf=ACYBGNTVhWyi6umZY0WZmyEGXQzpWGoeVg:1573689224689&source=lnt&tbs=sur:fc&sa=X&ved=0ahUKEwiqr-WZsejlAhWV4J4KHa6FDroQpwUIJA&biw=1920&bih=969&dpr=1 -->
		<img src = "footerimage.jpg" alt = "computer" id ="pic" />

	</footer>
	</body>
</html>