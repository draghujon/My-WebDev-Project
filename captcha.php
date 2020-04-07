<?php
	//$current_folder = dirname(__FILE__);
	session_start();
	//error_reporting(E_ALL); 
 	//ini_set('display_errors', 1);
	$permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	  
	function generate_string($input, $strength = 5) {
	    $input_length = strlen($input);
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $input[mt_rand(0, $input_length - 1)];
	        $random_string .= $random_character;
	    }
	    return $random_string;
	}

	$image = imagecreatetruecolor(200, 50);
	
	imageantialias($image, true);
	 
	$colors = [];
	 
	$red = rand(125, 175);
	$green = rand(125, 175);
	$blue = rand(125, 175);
	 
	for($i = 0; $i < 5; $i++) {
	  $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
	}

	imagefill($image, 0, 0, $colors[0]);

	for($i = 0; $i < 10; $i++) {
	  imagesetthickness($image, rand(2, 10));
	  $rect_color = $colors[rand(1, 4)];
	  imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $rect_color);
	}
	
	$black = imagecolorallocate($image, 0, 0, 0);
	$white = imagecolorallocate($image, 255, 255, 255);
	$textcolors = [$black, $white];

	$fonts = [dirname(__FILE__).'/fonts/Acme-Regular.ttf', dirname(__FILE__).'/fonts/Ubuntu-Regular.ttf', dirname(__FILE__).'/fonts/Merriweather-Regular.ttf', dirname(__FILE__).'/fonts/PlayfairDisplay-Regular.ttf'];

	$string_length = 6;
	$captcha_string = generate_string($permitted_chars, $string_length);
	$_SESSION['captcha_text'] = $captcha_string;
	for($i = 0; $i < $string_length; $i++) {
	  $letter_space = 170/$string_length;
	  $initial = 15;
	   
	  imagettftext($image, 20, rand(-15, 15), $initial + $i*$letter_space, rand(20, 40), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);

	}

	//$text_color = imagecolorallocate($image, 255, 255, 255); 
	//$test = rand(100000,999999);

	  
	  //$_POST['captcha'] = $captcha_string;
	  //$_SESSION['captcha_string'] = $_POST['captcha'];
		//$captcha = $_SESSION['captcha_text'];
	//var_dump($captcha_string);
	ob_start();
	//imagestring($image, 25, 50, 17,  $captcha_string, $text_color); 

	imagepng($image);
	$contents = ob_get_contents();
	ob_end_clean();
	imagedestroy($image);
	ob_clean();
	header("Content-type: image/png");
	
	echo $contents;
	//var_dump($captcha_string);

?>			 