<?php
/*
//здание пустого изображения
$image = imagecreatetruecolor(400, 300);
*/

//$image = imagecreatefrompng("_floor-2.png");

/*
print "<pre>";
print "image = ";
var_dump($image);
print "</pre>";
*/

//$im = imagecreatefrompng("test.png");
/*
header('Content-Type: image/png');

imagepng($image);
imagedestroy($image);
*/
/*
// заливка фона
$bg = imagecolorallocate($image, 0, 0, 0);
*/
/*
// выбор цвета для эллипса
$col_ellipse = imagecolorallocate($image, 255, 255, 255);

// рисование белого эллипса
imagefilledellipse($image, 200, 150, 300, 200, $col_ellipse);

// вывод картинки
header("Content-type: image/png");
imagepng($image);
*/


function LoadPNG($imgname)
{
    /* Attempt to open */
    $im = @imagecreatefrompng($imgname);

    /* See if it failed */
    if(!$im)
    {
        /* Create a blank image */
        $im  = imagecreatetruecolor(150, 30);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);

        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);

        /* Output an error message */
        imagestring($im, 1, 5, 5, 'Error loading ' . $imgname, $tc);
    }

    return $im;
}




header('Content-Type: image/png');

$img = LoadPNG('_floor-2.png');

if((count($_GET) > 0) && ($_GET["cx"]>0) && ($_GET["cy"] > 0)){
// выбор цвета для эллипса
$col_ellipse = imagecolorallocate($img, 0, 0, 0);

// рисование большого эллипса
imagefilledellipse($img, $_GET["cx"], $_GET["cy"], 30, 30, $col_ellipse);

// выбор цвета для эллипса
$col_ellipse = imagecolorallocate($img, 255, 0, 0);

// рисование малого эллипса
imagefilledellipse($img, $_GET["cx"], $_GET["cy"], 20, 20, $col_ellipse);

};


imagepng($img);
//imagedestroy($img);





?>
