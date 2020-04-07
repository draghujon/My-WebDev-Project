<?php 
 error_reporting(E_ALL); 
 ini_set('display_errors', 1);
  $test = rand(100000,999999);
  session_start();

  $_SESSION['captcha'] = $test;
// Create the size of image or blank image 
$image = imagecreate(150, 50); 
  
// Set the background color of image rgb(153, 102, 255)
$background_color = imagecolorallocate($image, 153, 102, 255); 
  
// Set the text color of image 
$text_color = imagecolorallocate($image, 255, 255, 255); 
  
// Function to create image which contains string. 
imagestring($image, 25, 50, 17,  $test, $text_color); 

  
header("Content-Type: image/png"); 
  
imagepng($image); 
imagedestroy($image); 
  
?> 