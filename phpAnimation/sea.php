<?php
$im=imageCreateTrueColor(500,400);
$red = imagecolorallocate($im,255,0,0);
$blue = imagecolorallocate($im,0,0,255);
$white = imagecolorallocate($im,255,255,255);
$cyan = imagecolorallocate($im,102,255,255);
$brown = imagecolorallocate($im,102,51,0);
$grey = imagecolorallocate($im,160,160,160);
$black = imagecolorallocate($im,0,0,0);

imagefill($im,20,20,$blue);

imagefilledrectangle($im,0,0,500,275,$cyan);

imagefilledellipse($im,50,270,100,100,$cyan);
imagefilledellipse($im,150,270,100,100,$cyan);
imagefilledellipse($im,250,270,100,100,$cyan);
imagefilledellipse($im,350,270,100,100,$cyan);
imagefilledellipse($im,450,270,100,100,$cyan);

$points=array(415,175,415,255,360,255);
imagefilledpolygon($im,$points,3,$white);

$points=array(425,175,485,255,425,255);
imagefilledpolygon($im,$points,3,$white);

imagefilledellipse($im,380,265,25,60,$red);

imagefilledellipse($im,380,225,30,30,$grey);

$points=array(350,260,490,265,470,310,370,310);
imagefilledpolygon($im,$points,4,$brown);

imagesetthickness($im,3);
imageline($im,420,265,420,165,$brown);

imagefilledellipse($im,130,380,70,180,$grey);

imagefilledellipse($im,130,305,10,10,$black);

imagesetthickness($im,3);
imageline($im,155,320,135,335,$black);

imagesetthickness($im,3);
imageline($im,135,335,160,330,$black);

$points=array(97,360,97,380,85,370);
imagefilledpolygon($im,$points,3,$grey);

imageGIF($im,"se.gif");

?>


