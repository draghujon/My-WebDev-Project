<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 12, 2020
    Description: Services PHP

----------------->
<?php
	session_start();
    require 'connect.php';
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
	
	
	if(isset($_SESSION['admin']))
	{
		if($_SESSION['admin'] === '1')
		{
			$adminMsg = "You are the admin!";
		}
	}
	else
	{
		$adminMsg = "You are not the admin!";
	}

        $query = "SELECT id, name, description, slug, price FROM services";

        $statement = $db->prepare($query); 

        $statement->execute(); 
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="cfstyles.css" />
	<title>Services</title>
	<script src="https://cdn.tiny.cloud/1/4mriwheprmpjqosi0mxoiv1cero7lggedrv83xjemydqaf1n/tinymce/5/tinymce.min.js" referrerpolicy="origin">
	</script>

	<script>
	    tinymce.init({
	      selector: '#inputDescription'
    	});
    </script>
</head>
<body>
	<header>
		<?php if(!isset($_SESSION['id'])): ?>
			<h1>Welcome to CF Computing : Services</h1>
		<?php else: ?>
			<h1>Welcome to CF Computing : Services<br></h1>
			<form action="services.php" method="post">
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

	<?php if(isset($_SESSION['admin'])): ?>
		<h2><a href="createServices.php">Create Services</a></h2>
	<?php endif ?>
	<h2>Welcome to my services page, please check below for more information!</h2>
	<h5><?= $adminMsg ?></h5>

	<?php if(isset($_SESSION['message'])): ?>
		<p><?= $_SESSION['message'] ?></p>
	<?php 
		unset($_SESSION['message']);
		endif; 
	?>
	
	<?php while ($row = $statement->fetch()): ?>
        <div id="services">
          <!-- <h4><a href="showServices.php?id=<?= $row['id'] ?>&title=<?= $row['slug'] ?>"> <?= $row['name'] ?></a></h4> -->
          <h4><a href="services/<?= $row['id'] ?>/<?= $row['slug'] ?>"> <?= $row['name'] ?></a></h4>
          <script>
                tinymce.init({
                  selector: <?= $row['description'] ?>,
                  plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
                  toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
                  toolbar_mode: 'floating',
                  tinycomments_mode: 'embedded',
                  tinycomments_author: 'Author name',
                });
            </script>
          <p>
            <?= $row['description'] ?>

          </p>
            
          <p>
            <small>
              <?= sprintf('$%.2f', $row['price']) ?>

              <?php if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1): ?>
              	<a href="updateDeleteServices.php?id=<?= $row['id'] ?>">edit</a>
<!--               <form action="updateDeleteServices.php?id={$row['id']}" method="GET">

                <input type="submit" name="command" value="edit">
              </form> -->
              <?php endif ?>
            </small>
          </p>

        </div>
    <?php endwhile ?>

	<footer id = "footer">
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