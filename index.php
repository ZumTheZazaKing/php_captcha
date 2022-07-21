<?php
    putenv('GDFONTPATH='.realpath('.'));

    //generate captcha string
    $permitted_chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    function generate_string($input, $strength=4){
        $input_length = strlen($input);
        $random_string = '';

        for($i=0;$i<$strength;$i++){
            $random_character = $input[mt_rand(0,$input_length-1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    $captcha_string = generate_string($permitted_chars);
    
    //create image
    $image = imagecreatetruecolor(200,50);
    imageantialias($image,true);


    //create background color
    $red = rand(125,175);
    $green = rand(125, 175);
    $blue = rand(125,175);

    $bgcolor = imagecolorallocate($image,$red,$green,$blue);

    imagefill($image,0,0,$bgcolor);


    //add text to image
    $black = imagecolorallocate($image,0,0,0);
    $white = imagecolorallocate($image,255,255,255);
    $text_colors = [$black, $white];
    $font = 'Ubuntu-Regular.ttf';

    for($i=0;$i<strlen($captcha_string);$i++){
        $letter_space = 170/strlen($captcha_string);
        $initial = 15;
        imagettftext($image,20,rand(-15,15),$initial+$i*$letter_space,rand(20,40),$text_colors[rand(0,1)],$font,$captcha_string[$i]);
    }


    //render random lines on image
    $line_color = imagecolorallocate($image, 0,0,0);
    for($i=0;$i<3;$i++){
        imageline(
            $image,
            mt_rand(0,200),
            mt_rand(0,50),
            mt_rand(0,200),
            mt_rand(0,50),
            $line_color
        );
    }


    //render noise points
    for($i=0;$i<10;$i++){
        imagefilledellipse(
            $image,
            mt_rand(0,200),
            mt_rand(0,50),
            2,
            3,
            $text_colors[0]
        );
    }

    //display image
    header('Content-type: image/jpeg');
    imagejpeg($image);
    imagedestroy($image);
?>