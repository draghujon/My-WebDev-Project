<?php
	session_start();
	include 'PHPMailer\PHPMailer\PHPMailer.php';
	include 'PHPMailer\PHPMailer\Exception.php';
	include 'PHPMailer\PHPMailer\SMTP.php';
	include 'ImageResize.php';
	include 'ImageResizeException.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require 'PHP\CodeCoverage\autoload.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Username = '11f9aa5ed30b58';
$mail->Password = 'adcf8274a7cac8';
$mail->SMTPSecure = 'tls';
$mail->Port = 2525;

$mail->setFrom($_SESSION['email'], $_SESSION['fname'] . " " . $_SESSION['lname']);
$mail->addAddress('cf-computing@icloud.com', 'Chris Feasby');

$mail->Subject = "Request for information";

$mail->isHTML(true);

//$mail->addEmbeddedImage($_IMAGE['image']['tmp_name'], 'image_cid');
$mail->Body = $_SESSION['feedback'];
$mail->AltBody = $_SESSION['fname'] . " " . $_SESSION['lname'] . " #: " . $_SESSION['customernum'];

if(!$mail->send()){
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}else{
    echo 'Message has been sent';
    header("Location: thankyou.php");
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>MailTrap.IO Message</title>
	<link rel="stylesheet" type="text/css" href="cfstyles.css">
</body>
</html>