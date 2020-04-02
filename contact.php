<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 12, 2020
    Description: Contact PHP

----------------->
<?php
	session_start();
	require 'PDFlib.php';
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
		else
		{

		}
	}
	else
	{

	}
function LoadJpeg($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefromjpeg($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a black image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}



function show_image($image, $width, $height) {
        //$this->helper('file');                   why need this?
        //$image_content = read_file($image);      We does not want to use this as output.

        //resize image           
        $image = imagecreatefromjpeg($image);
        $thumbImage = imagecreatetruecolor(50, 50);
        imagecopyresized($thumbImage, $image, 0, 0, 0, 0, 50, 50, $width, $height);
        imagedestroy($image);
        //imagedestroy($thumbImage); do not destroy before display :)
        ob_end_clean();  // clean the output buffer ... if turned on.
        header('Content-Type: image/jpeg');  
        imagejpeg($thumbImage); //you does not want to save.. just display
        imagedestroy($thumbImage); //but not needed, cause the script exit in next line and free the used memory
        exit;
  }

if(isset($_FILES['image']))
{

	
	require 'connect.php';

	// file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') 
    {
       $current_folder = dirname(__FILE__);
       
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];

       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

    function file_is_an_image($temporary_path, $new_path) 
    {
    		$allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png', 'document/pdf'];

        	global $allowed_file_extensions;
        	$allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png', 'pdf'];

	        global $actual_file_extension;
	        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);

	        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
	        
	        return $file_extension_is_valid;
    }

    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

    if ($image_upload_detected) 
    { 
        $image_filename        = $_FILES['image']['name'];
        $temporary_image_path  = $_FILES['image']['tmp_name'];
        $new_image_path        = file_upload_path($image_filename);

		//$image_base64 = base64_encode(file_get_contents($temporary_image_path));

        if (file_is_an_image($temporary_image_path, $new_image_path)) 
        {
            move_uploaded_file($temporary_image_path, $new_image_path);


            $image_file = basename($new_image_path);

            $query = "INSERT INTO images (name) VALUES (:imagename)";

			echo $image_file;
	        $statement = $db->prepare($query);
	        $statement->bindValue(':imagename', $image_file);
	        //$statement->bindValue(':image', $image);
			$statement->execute(); 
 
			//show_image($image_filename, 200, 200);
			$insert_id = $db->lastInsertId(); 
			//print_r($db->errorInfo());  // Result = Array ( [0] => 00000 [1] => [2] => )
        }
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="cfstyles.css" />
	<script type="text/javascript" src="formValidate.js"></script>
	<title>Contact Information</title>
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

		<div id="wrapper">
			<form id="contact" method="post" name="command" action="thankyou.php">
				<h3>Contact Us</h3>
				<p>Please enter your contact information</p>
				<fieldset>
					<legend>Customer Information</legend>
					<div class="formfield">
						<label for="fname" class="customer">First Name:</label>
						<input type="text" id="fname" name="fname" size="30" maxlength="15" value="" autofocus="autofocus" />					
						<div class="customer_error error" id="fname_error">* Please enter your first name.</div>
					</div>
				<div class="formfield">
					<label for="lname" class="customer">Last Name:</label>
					<input type="text" id = "lname" name="lname" size="30" maxlength="20" />
					<div class="customer_error error" id="lname_error">* Please enter your last name.</div>
				</div>
				<div class="formfield">
					<label for="customernum" class="customer">Phone Number:</label>
					<input type="text" id="customernum" name="customernum" size="20" maxlength="10" />
					<div class="customer_error error" id="customernum_error">* Please enter a valid phone number.</div>
				</div>
				<div class="formfield">
					<label for="email" class="customer">Email</label>
					<input id="email" name="email" type="text" />
					<div class="email_error error" id="email_error">* Required field</div>
					<div class="emailformat error" id="emailformat_error">* Invalid email address</div>
				</div>
				</fieldset>
				<fieldset>
					<legend>Comments Section</legend>
					<label for="feedback">Is there anything we can do to serve you better?</label><br />
					<textarea id="feedback" name="feedback" rows="8" cols="50"></textarea>
				</fieldset>


				<fieldset>
					<p class="center">
						<input type="submit" name="command" value="Submit" />
						<!--<button type="submit" value="Thanks" id="submit">Submit</button>-->
						<button type="reset" id="clear">Clear</button>
					</p>
				</fieldset>
			</form>

				<fieldset>

					<form method="post" action="contact.php" enctype="multipart/form-data">
						<label for="image">Image Filename: </label>
						<input type="file" name="image" id="image">
						<input type="submit" name="submit" value="Upload Image">
					</form>

				</fieldset>

				<fieldset>
					

						

						<?php if(isset($_FILES['image']) && $_FILES['image']['error'] > 0): ?>
							<p>Sorry, an error happened while uploading ur image. please try again. 
								Error Number: <?= $_FILES['image']['error'] ?></p>
            				<img class="imageupload" src="uploads\<?= $image_file ?>"/>
        				<?php elseif (isset($_FILES['image'])): ?>
        						<p>IN HERE</p>
            				<img class="imageupload" src="uploads\<?= $image_file ?>"/>
        				<?php endif ?>
						
				

				</fieldset>
		</div>


<?php if(isset($_FILES['image'])): ?>
		<?php if ($upload_error_detected): ?>

        <p>Error Number: <?= $_FILES['image']['error'] ?></p>

    <?php elseif ($image_upload_detected): ?>

        <p>Client-Side Filename: <?= $_FILES['image']['name'] ?></p>
        <p>Apparent Mime Type:   <?= $_FILES['image']['type'] ?></p>
        <p>Size in Bytes:        <?= $_FILES['image']['size'] ?></p>
        <p>Temporary Path:       <?= $_FILES['image']['tmp_name'] ?></p>

    <?php endif ?>
<?php endif ?>
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