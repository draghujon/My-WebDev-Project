<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 12, 2020
    Description: Contact PHP

----------------->
<?php
	session_start();

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


if(isset($_POST['command']))
{
	if($_POST['command'] === 'Submit')
	{
		//echo $_SESSION['captcha_text'];
		if(isset($_POST['captcha_challenge']) && $_POST['captcha_challenge'] == $_SESSION['captcha_text']) 
		{
			require 'connect.php';

			$fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
			$lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
			$customernum = filter_input(INPUT_POST, 'customernum', FILTER_SANITIZE_STRING);
			$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
			$feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_STRING);

	        $query = "INSERT INTO contact (fname, lname, phone, email, comments) VALUES (:fname, :lname, :customernum, :email, :feedback)";

			
	        $statement = $db->prepare($query);
	        $statement->bindValue(':fname', $fname);
	        $statement->bindValue(':lname', $lname);
	        $statement->bindValue(':customernum', $customernum);
	        $statement->bindValue(':email', $email);
	        $statement->bindValue(':feedback', $feedback);

			$statement->execute(); 
 
			$insert_id = $db->lastInsertId(); 

			$_SESSION['fname'] = $fname;
			$_SESSION['lname'] = $lname;
			$_SESSION['customernum'] = $customernum;
			$_SESSION['email'] = $email;

			if(empty($feedback))
			{
				$_SESSION['feedback'] = 'User did not enter any feedback.';
			}
			else
			{
				$_SESSION['feedback'] = $feedback;
			}

			header("Location: mailtrap.php");

			// require 'CaptchasDotNet.php';
			// $captchas = new CaptchasDotNet ('demo', 'secret',
   //                              '/tmp/captchasnet-random-strings','3600',
   //                              'abcdefghkmnopqrstuvwxyz','6',
   //                              '240','80','000088');
		} else {
        	echo '<p>You entered an incorrect Captcha.</p>';
			//unset($_SESSION['captcha_text']);
			//exit;
    }
			
	}
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

			$imgExt=strtolower(pathinfo($image_filename,PATHINFO_EXTENSION));
			$pic=rand(1000, 1000000).".".$imgExt;

			include 'ImageResize.php';
			include 'ImageResizeException.php';

			//echo file_upload_path($image_filename);
			$img = new \Gumlet\ImageResize(file_upload_path($image_filename));
			$img->resize(200, 300);
			//$img->interlace = 0;

			$img->save('uploads/' . 'thumb.' . $imgExt);
			
			// //$result = $img->getImageAsString(IMAGETYPE_PNG, 4);
			// $file = 'uploads/thumb.jpg';
			// $type = 'image/jpeg';
			// header('Content-Type:'.$type);
			// header('Content-Length: ' . filesize($file));
			// readfile($file);

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
			<form id="contact" method="post" name="command" action="contact.php" enctype="multipart/form-data">
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

					<form method="post" action="#" enctype="multipart/form-data">
						<label for="image">Image Filename: </label>
						<input type="file" name="image" id="image">
						<input type="submit" name="submit" value="Upload Image">
					</form>

				</fieldset>

				<fieldset>
					<div class="formfield">
						<?php 
						$captcha = $_SESSION['captcha_text'];
						 echo $_SESSION['captcha_text'];
						 
						?>
	
					    <label for="captcha" class="customer">Please Enter the Captcha Text</label>
					    <br />

					    <img src="captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fas fa-redo refresh-captcha"></i>
					    <br />
					    <input type="text" id="captcha" name="captcha_challenge" pattern="[A-Z]{6}">

					    <div class="customer_error error" id="captcha_error">* Please enter captcha!</div>
					</div>
				</fieldset>
				<fieldset>

						<?php if(isset($_FILES['image']) && $_FILES['image']['error'] > 0): ?>
							<p>Sorry, an error happened while uploading ur image. please try again. 
								Error Number: <?= $_FILES['image']['error'] ?></p>
            				<img class="imageupload" src="uploads\<?= $image_file ?>"/>
        				<?php elseif (isset($_FILES['image'])): ?>
            				<img src="uploads\thumb.<?= $imgExt ?>"  />
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
	<script>
		var refreshButton = document.querySelector(".refresh-captcha");
			refreshButton.onclick = function() {
			  document.querySelector(".captcha-image").src = 'captcha.php?' + Date.Now();
			}
	</script>
	</body>
</html>