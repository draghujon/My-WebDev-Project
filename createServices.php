<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 23, 2020
    Description: CreateServices PHP

----------------->

<?php

	session_start();
	if(isset($_POST['command']))
	{
		if($_POST['name'] === '' ||
		   $_POST['description'] === '' ||
		   $_POST['price'] === '')
		{
			$isTrue = false;
		}
		else
		{
			$isTrue = true;
		}

		if($_POST['command'] === 'Add Service' && $isTrue)
		{
			require 'connect.php';
			//Do the insert query
			$serviceName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
			$serviceDescription = filter_input(INPUT_POST, 'description');
			$servicePrice = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

			
			//echo "test" . $serviceDescription;
			//exit;
			$serviceDescription = strip_tags($serviceDescription, 
							'<a><cite><h2><p><tfoot><abbr><code><h3><pre><th><acronym><col><h4><small><thead><address><colgroup><h5><span><tr><b><dd><h6><strike><tt><big><div><hr><strong><u><blockquote><dl><html><sub><ul><body><dt><i><sup><br><em><img><table><caption><font><li><tbody><center><h1><ol><td>');

			$slug = preg_replace('/\s/','-', strtolower($serviceDescription)); 

				$query = "INSERT INTO services (name, description, slug, price)
					  VALUES (:name, :description, :slug, :price)";

				$statement = $db->prepare($query);
				$statement->bindValue(':name', $serviceName);;
				$statement->bindValue(':description', $serviceDescription);
				$statement->bindValue(':slug', $slug);
				$statement->bindValue(':price', $servicePrice);
				$statement->execute();

				$insert_id = $db->lastInsertId();
				
				header("Location: services.php");
			

		}
	}

?>
<!DOCTYPE html>
<html>	
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="cfstyles.css" />
    <script src="https://cdn.tiny.cloud/1/4mriwheprmpjqosi0mxoiv1cero7lggedrv83xjemydqaf1n/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
          selector: '#inputDescription'
        });
  </script>
	<title>CF Computing</title>
</head>

<body>
	<header>
		<?php if(!isset($_SESSION['id'])): ?>
			<h1>Welcome to CF Computing : Create Services</h1>
		<?php else: ?>
			<h1>Welcome to CF Computing : Create Services<br></h1>
			<form action="index.php" method="post">
				<h2>User: <?= $_SESSION['username'] ?></h2>
				<h2><input type="submit" name="act" value="logout"></h2>
			</form>
		<?php endif ?>
	</header>
	<nav id="banner">
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

<form action="createServices.php" method="post">
      <div id="content">

        <div>
          <label for="inputServiceName">Service Name</label>
          <input type="text" class="form-control" name="name" id="inputServiceName" placeholder="ServiceName">
        </div>

        <div>
          <label for="inputDescription">Description</label>
          <!--<input type="text" class="form-control" name="description" id="inputDescription" placeholder="Description">-->
          <textarea id="inputDescription" name="description" rows="8" cols="50"></textarea>
        </div>

        <div>
          <label for="inputPrice">Price</label>
          <input type="text" class="form-control" name="price" id="inputPrice">
        </div>
	            <script>
                tinymce.init({
                  selector: 'textarea',
                  plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
                  toolbar: 'a11ycheck addcomment showcomments casechange checklist code formatpainter pageembed permanentpen table',
                  toolbar_mode: 'floating',
                  tinycomments_mode: 'embedded',
                  tinycomments_author: 'Author name',
                });
            </script>
    
      <input type="submit" name="command" value="Add Service" />
    </form> 

  </div>
	<div class="mylogo">
		<!-- This is MY personal LOGO from my real business I ran so there is no credit for the image except to myself :) -->
	</div>

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
	<script src="main.js"></script>
	</body>
</html>