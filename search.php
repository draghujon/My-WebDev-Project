<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: April 06, 2020
    Description: Search PHP

----------------->

<?php
	$isError = false;
	if(isset($_POST['submit']))
	{
		require 'connect.php';
		$name = $_POST['search'];

		$query = "SELECT id, name, description, price FROM services WHERE name LIKE '%" . $name . "%'";

        $statement = $db->prepare($query); // Returns a PDOStatement object.
        //$statement->bindValue(':id', $id); 
        $statement->execute(); // The query is now executed.
        
        if(empty($statement->rowCount()))
        {
        	$isError = true;
			$error = "You must enter a search parameter!";
		}
	}
	




?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="cfstyles.css">
	<title>Search for a service</title>
</head>
<body>
	<header>
		<?php if(!isset($_SESSION['id'])): ?>
			<h1>Welcome to CF Computing : Contact</h1>
		<?php else: ?>
			<h1>Welcome to CF Computing : Contact<br></h1>
			<form action="contact.php" method="post">
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
	<?php while($row = $statement->fetch()): ?>
		<div id="services">
          <h4><a href="showServices.php?id=<?= $row['id'] ?>"> <?= $row['name'] ?></a></h4>
          <p>
            <?= $row['description'] ?>

          </p>
          <p>
            <small>
              <?= $row['price'] ?>

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
	
	<div id="services">
		<input type="button" value="Return to previous page" onClick="javascript:history.go(-1)">
    	<input type="button" value="Return home" onClick="location.href='index.php'">
    </div>

	    <?php if($isError): ?>
	    	<div id="services">
	    		<p> <?= $error ?> </p>
	    	</div>
	    <?php endif ?>
	

</body>
</html>